import axios from 'axios';
import authHeader from './auth-header';

class AdminService {
  async postAdmin(user) {
      return await axios.post('admin',user, { headers: authHeader() })
  }
}

export default new AdminService();