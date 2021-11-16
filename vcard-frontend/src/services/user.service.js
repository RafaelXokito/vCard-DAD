import axios from 'axios';
import authHeader from './auth-header';

class UserService {
  getPublicContent() {
    //return axios.get(API_URL + 'vcards');
  }

  async getUserBoard(link='users?page=1') {
    return await axios.get(link, { headers: authHeader() });
  }

  getUser(id){
    return axios.get('users/' + id, { headers: authHeader() });
  }

  getAdminBoard() {
    //return axios.get(API_URL + 'administrators', { headers: authHeader() });
  }
}

export default new UserService();