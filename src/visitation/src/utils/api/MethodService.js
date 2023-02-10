import HTTPClient from './HTTPClient';

export default class MethodService extends HTTPClient {
    static getMethods() {
        return MethodService.get(`/visitation/methods`).then(response => response.methods);
    }

    static getMethod(methodId) {
        return MethodService.get(`/visitation/methods/${methodId}`).then(response => response.method);
    }

    static createMethod(method) {
        return MethodService.get(`/visitation/methods`, method).then(response => response.method);
    }

    static updateMethod(methodId, method) {
        return MethodService.get(`/visitation/methods/${methodId}`, method);
    }

    static deleteMethod(methodId) {
        return MethodService.get(`/visitation/methods/${methodId}`);
    }
}