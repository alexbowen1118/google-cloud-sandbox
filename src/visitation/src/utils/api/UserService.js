import HTTPClient from './HTTPClient';

export default class UserService extends HTTPClient {
    static getUsers() {
        return UserService.get(`/users`).then(response => response.users);
    }
}