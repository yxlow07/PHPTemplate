async function savePhoto(inp)
{
    let formData = new FormData();
    let photo = inp.files[0];

    formData.append("photo", photo);

    const ctrl = new AbortController()    // timeout
    setTimeout(() => ctrl.abort(), 5000);

    try {
        let r = await fetch('./upload/pfp', {method: "POST", body: formData, signal: ctrl.signal});
        let json_response = JSON.parse(JSON.stringify(await r.json()));

        // Check if status is 200
        if (r.status === 200) {
            if (json_response.status) {
                // TODO: display friendly messages
                alert("Profile Picture successfully updated")
                location.reload()
            } else {
                alert("Unable to post profile picture. Contact a developer to check his handling of profile picture")
            }
        } else {
            alert("Unable to post profile picture. Contact a developer to check his posting location of the file")
        }
    } catch(e) {
        console.warn('Something went wrong:', e);
        console.debug('Contact a developer and screenshot what you see above')
    }

}

$(function () {
    $("#pfp").draggable();
})