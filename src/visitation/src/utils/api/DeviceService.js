import HTTPClient from './HTTPClient';

export default class DeviceService extends HTTPClient {
    static getDevices() {
        return DeviceService.get(`/visitation/devices`).then(response => response.devices);
    }

    static getDevice(deviceId) {
        return DeviceService.get(`/visitation/devices/${deviceId}`).then(response => response.device);
    }

    static getDevicesByPark(parkId) {
        return DeviceService.get(`/visitation/parks/${parkId}/devices`).then(response => response.device);
    }

    static createDevice(device) {
        return DeviceService.post(`/visitation/devices`, device).then(response => response.device);
    }

    static updateDevice(deviceId, device) {
        return DeviceService.put(`/visitation/devices/${deviceId}`, device);
    }

    static deleteDevice(deviceId) {
        return DeviceService.delete(`/visitation/devices/${deviceId}`);
    }


}