import axios from 'axios';

const API_BASE = '/api';

export default class HTTPClient {

  static errorHandler(error) {
    if (error.response) {

      if (error.response.status === 401) {
        alert("You have been signed out the authentication. Please log in again to continue.")
        window.location.reload();
      } else if (error.response.status == 403) {
        alert("Unauthorized")
        window.location.reload()
      }
      
    }

    console.log("HTTPClient Error", {...error});
    error = error.response ? error.response.data : error;
    return Promise.reject(error);
  }

  static successHandler(response) {
    const responseData = response.data;
    if(responseData.result !== "success")
      return Promise.reject(responseData.message);
    return responseData;
  }

  static get(url) {
    return axios.get(`${API_BASE}${url}`)
    .then(HTTPClient.successHandler)
    .catch(HTTPClient.errorHandler);
  }

  static get(url, body) {
    return axios.get(`${API_BASE}${url}`, body)
        .then(HTTPClient.successHandler)
        .catch(HTTPClient.errorHandler);
  }

  static post(url, body) {
    return axios.post(`${API_BASE}${url}`, body)
    .then(HTTPClient.successHandler)
    .catch(HTTPClient.errorHandler);
  }

  static put(url, body) {
    return axios.put(`${API_BASE}${url}`, body)
    .then(HTTPClient.successHandler)
    .catch(HTTPClient.errorHandler);
  }

  static delete(url) {
    return axios.delete(`${API_BASE}${url}`)
    .then(HTTPClient.successHandler)
    .catch(HTTPClient.errorHandler);
  }

}