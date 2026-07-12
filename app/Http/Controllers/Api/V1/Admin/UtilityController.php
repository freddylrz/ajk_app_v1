<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Helpers\Api\Init;
use Log;
use Throwable;


class UtilityController extends Controller
{
    public function userList(Request $r)
    {
        try {
            $data = DB::table('users as u')
                ->leftJoin('user_branch as ub', 'u.id', '=', 'ub.user_id')
                ->leftJoin('master.tb_branch as b', 'ub.branch_id', '=', 'b.branch_id')
                ->leftJoin('role_user as ru', 'u.id', '=', 'ru.user_id')
                ->leftJoin('roles as r', 'ru.role_id', '=', 'r.id')
                ->select(
                    'u.id as user_id',
                    'u.display_name',
                    'u.name as username',
                    'u.is_active',
                    DB::raw("
                        CASE
                            WHEN u.is_active = 1 THEN 'Aktif'
                            WHEN u.is_active = 0 THEN 'Tidak Aktif'
                            ELSE '-'
                        END AS is_active_desc
                    "),
                    'u.created_at',
                    DB::raw("STRING_AGG(DISTINCT b.branch_name, ', ' ORDER BY b.branch_name) AS branch_name"),
                    DB::raw("STRING_AGG(DISTINCT r.name, ', ' ORDER BY r.name) AS role_name")
                )
                ->groupBy(
                    'u.id',
                    'u.display_name',
                    'u.name',
                    'u.is_active',
                    'u.created_at'
                )
                ->orderBy('u.display_name')
                ->get();

            return response()->json([
                'status' => 200,
                'message' => "Berhasil memuat data!",
                'data' => [
                    'list' => $data
                ]
            ], 200);
        } catch (Throwable $exception) {
            Log::error($exception);

            return response()->json([
                'status' => 500,
                'message' => "Terjadi kesalahan! Silakan coba lagi.",
            ], 500);
        }
    }

    public function userAsset(Request $r)
    {
        try {

            $role = DB::table('roles')
                ->select(
                    'id as role_id',
                    'name as role_name'
                )
                ->orderBy('name')
                ->get();

            $branch = DB::table('master.tb_branch')
                ->select(
                    'branch_id',
                    'branch_name'
                )
                ->orderBy('branch_name')
                ->get();

            return response()->json([
                'status' => 200,
                'message' => 'Success',
                'data' => [
                    'role' => $role,
                    'branch' => $branch,
                ]
            ], 200);

        } catch (Throwable $exception) {

            Log::error($exception);

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan! Silakan coba lagi.',
            ], 500);
        }
    }

    public function userInsert(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'display_name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'min:8',
                'max:100',
                'regex:/^[A-Za-z0-9]+$/',
                'unique:users,name',
            ],
            'password' => 'required|string|min:8',
            'is_active' => 'required|boolean',
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'required|integer|exists:roles,id',
            'branch_ids' => 'required|array|min:1',
            'branch_ids.*' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $userId = Init::createId();

            DB::table('users')->insert([
                'id' => $userId,
                'display_name' => trim($r->display_name),
                'name' => trim($r->username),
                'email' => null,
                'email_verified_at' => null,
                'password' => Hash::make($r->password),
                'is_active' => $r->is_active,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $roles = [];

            foreach ($r->role_ids as $roleId) {
                $roles[] = [
                    'user_id' => $userId,
                    'role_id' => $roleId,
                ];
            }

            DB::table('role_user')->insert($roles);

            $branches = [];

            foreach ($r->branch_ids as $branchId) {
                $branches[] = [
                    'user_id' => $userId,
                    'branch_id' => $branchId,
                ];
            }

            DB::table('user_branch')->insert($branches);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => "User berhasil ditambahkan.",
            ], 200);

        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error($exception);

            return response()->json([
                'status' => 500,
                'message' => "Terjadi kesalahan! Silakan coba lagi.",
            ], 500);
        }
    }

    public function userUpdate(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'user_id' => 'required|exists:users,id',
            'display_name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'min:8',
                'max:100',
                'regex:/^[A-Za-z0-9]+$/',
                Rule::unique('users', 'name')->ignore($r->user_id, 'id'),
            ],
            'password' => 'nullable|string|min:8',
            'is_active' => 'required|boolean',
            'role_ids' => 'required|array|min:1',
            'role_ids.*' => 'required|integer|exists:roles,id',
            'branch_ids' => 'required|array|min:1',
            'branch_ids.*' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $update = [
                'display_name' => trim($r->display_name),
                'name' => trim($r->username),
                'is_active' => $r->is_active,
                'updated_at' => now(),
            ];

            if (!empty($r->password)) {
                $update['password'] = Hash::make($r->password);
            }

            DB::table('users')
                ->where('id', $r->user_id)
                ->update($update);

            DB::table('role_user')
                ->where('user_id', $r->user_id)
                ->delete();

            $roles = [];

            foreach ($r->role_ids as $roleId) {
                $roles[] = [
                    'user_id' => $r->user_id,
                    'role_id' => $roleId,
                ];
            }

            DB::table('role_user')->insert($roles);

            DB::table('user_branch')
                ->where('user_id', $r->user_id)
                ->delete();

            $branches = [];

            foreach ($r->branch_ids as $branchId) {
                $branches[] = [
                    'user_id' => $r->user_id,
                    'branch_id' => $branchId,
                ];
            }

            DB::table('user_branch')->insert($branches);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'User berhasil diperbarui.',
            ], 200);

        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error($exception);

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan! Silakan coba lagi.',
            ], 500);
        }

    }
    public function userDelete(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $user = DB::table('users')
                ->where('id', $r->user_id)
                ->first();

            if (!$user) {
                return response()->json([
                    'status' => 404,
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }

            DB::table('role_user')
                ->where('user_id', $r->user_id)
                ->delete();

            DB::table('user_branch')
                ->where('user_id', $r->user_id)
                ->delete();

            DB::table('personal_access_tokens')
                ->where('tokenable_id', $r->user_id)
                ->where('tokenable_type', 'App\\Models\\User')
                ->delete();

            DB::table('sessions')
                ->where('user_id', $r->user_id)
                ->delete();

            DB::table('users')
                ->where('id', $r->user_id)
                ->delete();

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'User berhasil dihapus.',
            ], 200);

        } catch (Throwable $exception) {

            DB::rollBack();
            Log::error($exception);

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan! Silakan coba lagi.',
            ], 500);
        }
    }

    public function branchList(Request $r)
    {
        try {

            $data = DB::table('master.tb_branch')
                ->select(
                    'branch_id',
                    'branch_name',
                    'created_at'
                )
                ->orderBy('branch_name')
                ->get();

            return response()->json([
                'status' => 200,
                'message' => 'Success',
                'data' => $data
            ], 200);

        } catch (Throwable $exception) {

            Log::error($exception);

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan! Silakan coba lagi.',
            ], 500);
        }
    }

    public function branchInsert(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'branch_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $check = DB::table('master.tb_branch')
            ->whereRaw('LOWER(branch_name)=?', [strtolower(trim($r->branch_name))])
            ->exists();

        if ($check) {
            return response()->json([
                'status' => 422,
                'message' => 'Nama cabang sudah terdaftar.'
            ], 422);
        }

        DB::beginTransaction();

        try {

            DB::table('master.tb_branch')->insert([
                'branch_name' => trim($r->branch_name),
                'created_at' => now(),
                'updated_at' => now(),
                'user_id_add' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Cabang berhasil ditambahkan.',
            ], 200);

        } catch (Throwable $exception) {

            DB::rollBack();
            Log::error($exception);

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan! Silakan coba lagi.',
            ], 500);
        }
    }

    public function branchUpdate(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'branch_id' => 'required|integer',
            'branch_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $check = DB::table('master.tb_branch')
            ->whereRaw('LOWER(branch_name)=?', [strtolower(trim($r->branch_name))])
            ->where('branch_id', '!=', $r->branch_id)
            ->exists();

        if ($check) {
            return response()->json([
                'status' => 422,
                'message' => 'Nama cabang sudah terdaftar.'
            ], 422);
        }

        DB::beginTransaction();

        try {

            DB::table('master.tb_branch')
                ->where('branch_id', $r->branch_id)
                ->update([
                    'branch_name' => trim($r->branch_name),
                    'updated_at' => now(),
                ]);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Cabang berhasil diperbarui.',
            ], 200);

        } catch (Throwable $exception) {

            DB::rollBack();
            Log::error($exception);

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan! Silakan coba lagi.',
            ], 500);
        }
    }

    public function branchDelete(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'branch_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $used = DB::table('user_branch')
            ->where('branch_id', $r->branch_id)
            ->exists();

        if ($used) {
            return response()->json([
                'status' => 422,
                'message' => 'Cabang masih digunakan oleh user.'
            ], 422);
        }

        DB::beginTransaction();

        try {

            DB::table('master.tb_branch')
                ->where('branch_id', $r->branch_id)
                ->delete();

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Cabang berhasil dihapus.',
            ], 200);

        } catch (Throwable $exception) {

            DB::rollBack();
            Log::error($exception);

            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan! Silakan coba lagi.',
            ], 500);
        }
    }
}
