import React from 'react';
import ReactDOM from 'react-dom/client';


const URL_LOCAL = 'api/';

// Date functions

export function dateChangeFormat(dateString) {
    const zeroPad = (val) => val.toString().padStart(2, "0");
    let odate = new Date(dateString);
    let year = odate.getFullYear();
    let month = zeroPad(odate.getMonth());
    let day = zeroPad(odate.getDate());
    let hour = zeroPad(odate.getHours());
    let mins = zeroPad(odate.getMinutes());
    return `${day}/${month}/${year} | ${hour}:${mins} hs`;
}




// NumberFormat
const splitThousands = (number) => (dec_point, thousands_point) => {
    const splitNum = number.toString().split(dec_point);
    splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
    return splitNum.join(dec_point);
};

const isBigNumber = (number) => number.toString().includes("e");

const isBigFloat = (number) => number.toString().includes("-");

const calcTrailing = (dec, len) => Number(dec) + 2 - len;

const handleBigFloats = (number, decimals) => {
    if (!decimals) {
        return "0";
    }

    const [numbers, dec] = number.toString().replace(".", "").split("e-");
    const trailingZeros = calcTrailing(dec, numbers.length);
    const res = `${"0.".padEnd(trailingZeros + 2, "0")}${numbers}`;

    return decimals ? res.substring(0, 2) + res.substring(2, decimals + 2) : res;
};

const handleBigNumbers = (number, decimals, dec_point, thousands_point) => {
    if (isBigFloat(number)) {
        return handleBigFloats(number, decimals);
    }

    return splitThousands(BigInt(number))(dec_point, thousands_point);
};

function handleFiniteNumbers(number, decimals, dec_point, thousands_point) {
    // if (!isFinite(number)) {
    //     throw new TypeError("number is not finite number");
    // }

    if (!decimals) {
        const len = number.toString().split(".").length;
        decimals = len > 1 ? len : 0;
    }

    return splitThousands(
        parseFloat(number).toFixed(decimals).replace(".", dec_point)
    )(dec_point, thousands_point);
}

export const numberFormat = (
    number,
    decimals = 2,
    dec_point = ".",
    thousands_point = ","
) => {
    number = parseFloat(number);
    if (number == null || typeof number !== "number") {
        throw new TypeError("number is not valid");
    }

    if (isBigNumber(number)) {
        return handleBigNumbers(number, decimals, dec_point, thousands_point);
    }

    return handleFiniteNumbers(number, decimals, dec_point, thousands_point);
};


// Fetch functions
function getHeader() {
    return {
        "Authorization": `Bearer ${localStorage.getItem("tokenUser")}`
    };
}

const buildDateSend = (data) => {
    let dataSend = new FormData();
    for (let key in data) { dataSend.append(key, data[key]); }
    return dataSend;
}

export const Logger = (params) => {
    params.logger = true;
    let formData = buildDateSend(params);
    const config = { mode: "cors", method: 'POST', body: formData };
    fetch('', config);
}
export const localData = async (params) => {
    const token = false;
    const url = URL_LOCAL;
    let formData = buildDateSend(params);
    const config = token ? { mode: "cors", method: 'POST', body: formData, headers: getHeader() } : { mode: "no-cors", method: 'POST', body: formData };
    const response = await fetch(url, config);
    return await response.json();
}

export const API = {
    _localRquest: function _localRquest(hook) {
        const token = false;
        const url = '';
        const params = {};
        params[hook] = true;
        let formData = buildDateSend(params);
        const config = token ? { mode: "cors", method: 'POST', body: formData, headers: getHeader() } : { mode: "cors", method: 'POST', body: formData };
        return fetch(url, config).then(function (response) {
            return response.json();
        });
    },

    _post: function _post(url, params, token = true) {
        let formData = buildDateSend(params);
        const config = token ? { mode: "cors", method: 'POST', body: formData, headers: getHeader() } : { mode: "cors", method: 'POST', body: formData };
        return fetch(url, config).then(function (response) {
            return response.json();
        });
    },

    _get: function _get(url, params, token = true) {
        const query = new URLSearchParams(params);
        const newUrl = params ? new URL(`${url}${query ? `?${query.toString()}` : ''}`) : url;
        const config = token ? { method: 'GET', headers: getHeader() } : { method: 'GET' };
        return fetch(newUrl, config).then(function (response) {
            return response.json(); y
        });
    },

    _put: function _put(url, params, token = true) {
        let formData = buildDateSend(params);
        formData.append('_method', 'PUT');
        const config = token ? { mode: "cors", method: 'POST', body: formData, headers: getHeader() } : { mode: "cors", method: 'POST', body: formData };
        return fetch(url, config).then(function (response) {
            return response.json();
        });
    },

    _delete: function _delete(url, token = true) {
        const config = token ? { mode: "cors", method: 'DELETE', headers: getHeader() } : { mode: "cors", method: 'DELETE' };
        return fetch(url, config).then(function (response) {
            return response.json();
        });
    }

} 
