import HTTPClient from './HTTPClient';

export default class ModelService extends HTTPClient {
    static getModels() {
        return ModelService.get(`/visitation/models`).then(response => response.models);
    }

    static getModel(modelId) {
        return ModelService.get(`/visitation/models/${modelId}`).then(response => response.model);
    }

    static createModel(model) {
        return ModelService.post(`/visitation/models`, model).then(response => response.model);
    }

    static updateModel(modelId, model) {
        return ModelService.put(`/visitation/models/${modelId}`, model);
    }

    static deleteModel(modelId) {
        return ModelService.delete(`/visitation/models/${modelId}`);
    }
}