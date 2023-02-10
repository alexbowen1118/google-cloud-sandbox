import HTTPClient from './HTTPClient';

export default class FunctionService extends HTTPClient {
    static getFunctions() {
        return FunctionService.get(`/visitation/functions`).then(response => response.functions);
    }

    static getFunction(functionId) {
        return FunctionService.get(`/visitation/functions/${functionId}`).then(response => response.function);
    }

    static createFunction(func) {
        return FunctionService.post(`/visitation/functions`, func).then(response => response.function);
    }

    static updateFunction(functionId, func) {
        return FunctionService.put(`/visitation/functions/${functionId}`, func).then(response => response.function);
    }

    static deleteFunction(functionId) {
        return FunctionService.delete(`/visitation/functions/${functionId}`);
    }
}