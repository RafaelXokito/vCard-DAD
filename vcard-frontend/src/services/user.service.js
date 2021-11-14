import axios from 'axios';
import authHeader from './auth-header';

class UserService {
  getPublicContent() {
    //return axios.get(API_URL + 'vcards');
  }

  getUserBoard() {
    return axios.get('users', { headers: authHeader() });
  }

  getUser(id){
    return axios.get('users/' + id, { headers: authHeader() });
  }

  getAdminBoard() {
    //return axios.get(API_URL + 'administrators', { headers: authHeader() });
  }
}

export default new UserService();