async function getDecryptedAccessTokenData(cookiesName) {
    const name = cookiesName
    const decodedCookie = decodeURIComponent(document.cookie);
    const cookieArray = decodedCookie.split(';');

    for (let i = 0; i < cookieArray.length; i++) {
        let cookie = cookieArray[i].trim();
        if (cookie.startsWith(name)) {
            const token = cookie.substring(name.length);
            return token;
        }
    }

    return null;
}

export async function getAccessTokenFromCookies() {
    const data = await getDecryptedAccessTokenData("__ajk-tib-at=");
    return data;
}

export async function getRoleFromCookies() {
    const data = await getDecryptedAccessTokenData();
    return data?.roles ?? null;
}
export async function getBranchFromCookies() {
    const data = await getDecryptedAccessTokenData();
    return data?.branches ?? null;
}

export async function getExpireAccessTokenFromCookies() {
    const data = await getDecryptedAccessTokenData();
    return data;
}

export async function getRefreshAccessTokenFromCookies() {
    const data = await getDecryptedAccessTokenData("__ajk-tib-rt=");
    return data;
}

export async function getUserIdFromCookies() {
    const data = await getDecryptedAccessTokenData();
    return data?.user_id ?? null;
}
