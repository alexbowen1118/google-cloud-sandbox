import HTTPClient from './HTTPClient';

export default class CounterRulesService extends HTTPClient {
    static getCounterRules(deviceId) {
        return CounterRulesService.get(`/visitation/devices/${deviceId}/counter_rules`).then(response => response.counter_rules);
    }

    static getCounterRule(deviceId, ruleId) {
        return CounterRulesService.get(`/visitation/devices/${deviceId}/counter_rules/{ruleId}`).then(response => response.counter_rule);
    }

    static createCounterRules(deviceId, rule) {
        return CounterRulesService.post(`/visitation/devices/${deviceId}/counter_rules`, rule).then(response => response.counter_rule);
    }

    static updateCounterRules(deviceId, rule, ruleId) {
        return CounterRulesService.put(`/visitation/devices/${deviceId}/counter_rules/${ruleId}`, rule);
    }

    static deleteCounterRules(deviceId, ruleId) {
        return CounterRulesService.delete(`/visitation/devices/${deviceId}/counter_rules/{$ruleId}`);
    }
}