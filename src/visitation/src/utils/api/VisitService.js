import HTTPClient from './HTTPClient';

export default class VisitService extends HTTPClient {
    static getVisits() {
        return VisitService.get(`/visitation/visits`).then(response => response.visits);
    }

    static getMonthVisits() {
        return VisitService.get(`/visitation/visits/month`,).then(response => response.visits);
    }

    static getVisitsByDevice(deviceId) {
        return VisitService.get(`/visitation/devices/${deviceId}/visits`).then(response => response.visits);
    }

    static getVisit(deviceId, visitId) {
        return VisitService.get(`/visitation/devices/${deviceId}/visits/${visitId}`).then(response => response.visit);
    }

    static getVisitsByPark(parkId) {
        return VisitService.get(`/visitation/parks/${parkId}/visits`).then(response => response.visits);
    }

    static getDayVisitsByPark(parkId) {
        return VisitService.get(`/visitation/parks/${parkId}/visits/day`,).then(response => response.visits);
    }

    static getAllTimeVisitsByMonth() {
        return VisitService.get(`/visitation/parks/visits/month`,).then(response => response.visits);
    }

    // static getVisitsByYear(deviceId, visitId, year) {
    //     return VisitService.get(`/visitation/devices/${deviceId}/visits/${year}`).then(response => response.visits);
    // }
    //
    // static getVisitsByMonth(deviceId, visitId, year, month) {
    //     return VisitService.get(`/visitation/devices/${deviceId}/visits/${year}/${month}`).then(response => response.visits);
    // }
    //
    // static getVisitsByDay(deviceId, visitId, year, month, day) {
    //     return VisitService.get(`/visitation/devices/${deviceId}/visits/${year}/${month}/${day}`).then(response => response.visits);
    // }
    //
    // static getVisitsByHour(deviceId, visitId, year, month, day, hour) {
    //     return VisitService.get(`/visitation/devices/${deviceId}/visits/${year}/${month}/${day}/${hour}`).then(response => response.visits);
    // }

    static createVisit(deviceId, visit) {
        return VisitService.post(`/visitation/devices/${deviceId}/visits`, visit).then(response => response.visit);
    }

    static updateVisit(deviceId, visitId, visit) {
        return VisitService.put(`/visitation/devices/${deviceId}/visits/${visitId}`, visit);
    }

    static deleteVisit(deviceId, visitId) {
        return VisitService.delete(`/visitation/devices/${deviceId}/visits/${visitId}`);
    }

}