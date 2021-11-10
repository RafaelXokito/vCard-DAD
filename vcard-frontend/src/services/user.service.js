import axios from 'axios';
import authHeader from './auth-header';

const API_URL = 'http://localhost/api/';

class UserService {
  getPublicContent() {
    //return axios.get(API_URL + 'vcards');
  }

  getUserBoard() {
    return axios.get(API_URL + 'users', { headers: authHeader() });
  }

  getUser(username){
    return axios.get(API_URL + 'users/' + username, { headers: authHeader() });
  }

  getAdminBoard() {
    //return axios.get(API_URL + 'administrators', { headers: authHeader() });
  }
}

export default new UserService();