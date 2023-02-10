import HTTPClient from './HTTPClient';

export default class TypeService extends HTTPClient {
    static getTypes() {
        return TypeService.get(`/visitation/types`).then(response => response.types);
    }

    static getType(typeId) {
        return TypeService.get(`/visitation/types/${typeId}`).then(response => response.type);
    }

    static createType(type) {
        return TypeService.post(`/visitation/types`, type).then(response => response.type);
    }

    static updateType(typeId, type) {
        return TypeService.put(`/visitation/types/${typeId}`, type);
    }

    static deleteType(typeId) {
        return TypeService.delete(`/visitation/types/${typeId}`);
    }
}