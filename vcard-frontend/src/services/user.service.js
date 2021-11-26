import axios from 'axios';
import authHeader from './auth-header';

class UserService {
  getPublicContent() {
    //return axios.get(API_URL + 'vcards');
  }

  async getUserBoard(link='users?page=1') {
    return await axios.get(link, { headers: authHeader() });
  }

  get(username){
    return axios.get('users/' + username, { headers: authHeader() });
  }

  getMe(){
    return axios.get('users/me', { headers: authHeader() });
  }

  getAdminBoard() {
    //return axios.get(API_URL + 'administrators', { headers: authHeader() });
  }
}

export default new UserService();