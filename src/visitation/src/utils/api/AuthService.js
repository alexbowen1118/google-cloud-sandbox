import HTTPClient from './HTTPClient';

export default class AuthService extends HTTPClient {
    static login(credentials) {
        return AuthService.post('/auth/login', credentials).then(console.log());
    }
}