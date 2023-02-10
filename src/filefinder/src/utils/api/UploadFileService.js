const API_FILEFINDER_BASE = `https://localhost/api/filefinder`;

export default class UploadFileService {

  static uploadFiles(formdata) {
    const requestOptions = {
      method: "POST",
      body: formdata,
      redirect: "follow",
    };

    return fetch(`${API_FILEFINDER_BASE}/upload`, requestOptions)
      .then((res) => res.json())
      .catch((err) => console.log("ERROR", err));
  }
  
}
