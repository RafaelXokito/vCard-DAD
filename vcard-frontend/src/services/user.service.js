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

  async getMe(){
    return await axios.get('users/me', { headers: authHeader() });
  }

  async updateUser(user){
    return await axios.patch(`users/${user.username}`,user, { headers: authHeader() });
  }

  async updateUserPhoto(form){
    return await axios.post(`users/${form.get("username")}/updateUserPhoto`,form, { headers: authHeader(`multipart/form-data; charset=utf-8;`) });
  }

  async updatePasswordUser(user) {
    return await axios.patch(`users/${user.username}/updatePassword`,user, { headers: authHeader() });
  }

  async updateConfirmationCodeUser(user) {
    return await axios.patch(`users/${user.username}/updateConfirmationCode`,user, { headers: authHeader() });
  }
  

  getAdminBoard() {
    //return axios.get(API_URL + 'administrators', { headers: authHeader() });
  }
}

export default new UserService();