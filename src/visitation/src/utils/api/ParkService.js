import HTTPClient from './HTTPClient';

export default class ParkService extends HTTPClient {
    static getParks() {
        return ParkService.get(`/parks`).then(response => response.parks);
    }

    static getPark(parkId) {
        return ParkService.get(`/parks/${parkId}`).then(response => response.prk);
    }
}