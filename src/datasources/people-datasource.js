const API_HOST = 'http://127.0.0.1:8000/api'
export async function bulkUploadPeopleCsv(file) {
    let formData = new FormData()
    formData.append('file', file)
    try {
        let response = await fetch(`${API_HOST}/people/bulk-upload`, {
            method: 'POST',
            body: formData
        })
        if (response.status === 201) {
            return Promise.resolve(response.json())
        }
        console.log("Server returned non 2xx status code")
        return Promise.reject({ message: "File upload failed" })
    } catch (err) {
        console.log(err.message)
        return Promise.reject({ message: "An unexpected error occured" })
    }
}
