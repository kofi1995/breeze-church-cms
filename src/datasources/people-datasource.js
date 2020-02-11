const API_HOST = 'http://127.0.0.1:8000/api'
export async function bulkUploadPeopleCsv(file) {
    try {
        let response = await doUploadFile(file, 'people/bulk-upload')
        if (response.status === 201) {
            return Promise.resolve(response.json())
        }
        console.log("Server returned non 2xx status code")
        return Promise.reject({ message: "File upload failed" })
    } catch (err) {
        console.log(err.message)
        return Promise.reject({ message: "An unexpected error occurred" })
    }
}

async function ajaxJSONFetch(path, body = {}, method = "GET"){
    let objectToSend = {
        method: method,
        headers: new Headers({
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }),
    };

    if(method !== "GET" && method !== "HEAD") {
        objectToSend.body = JSON.stringify(body);
    }

    return fetch(path, objectToSend)
}

async function doUploadFile(file,path){
    let formData = new FormData()
        formData.append('file', file)
        return fetch(`${API_HOST}/${path}`, {
            method: 'POST',
            body: formData,
            headers: new Headers({'Accept': 'application/json'}),
        })
}

export async function bulkUploadGroupCsv(file) {
    try {
        let response = await doUploadFile(file, 'groups/bulk-upload')
        if (response.status === 201) {
            return Promise.resolve(response.json())
        }
        console.log("Server returned non 2xx status code")
        return Promise.reject({ message: "File upload failed" })
    } catch (err) {
        console.log(err.message)
        return Promise.reject({ message: "An unexpected error occurred" })
    }
}

export async function updatePerson(person) {
    let res = await ajaxJSONFetch(`${API_HOST}/people/${person.id}`, person, 'PUT');
    return await res.text()
}

export async function fetchPeople() {
    let res = await ajaxJSONFetch(`${API_HOST}/people`);
    let data = await res.json()
    return data.data
}

export async function fetchGroups() {
    let res = await ajaxJSONFetch(`${API_HOST}/groups`);
    let data = await res.json()
    return data.data
}
