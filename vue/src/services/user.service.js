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

  async updateVCardPhoto(form){
    return await axios.post(`vcards/${form.get("username")}/updateVCardPhoto`,form, { headers: authHeader(`multipart/form-data; charset=utf-8;`) });
  }

  async updatePasswordUser(user) {
    return await axios.patch(`users/${user.username}/updatePassword`,user, { headers: authHeader() });
  }

  async updateConfirmationCodeUser(user) {
    return await axios.patch(`users/${user.username}/updateConfirmationCode`,user, { headers: authHeader() });
  }

  async delete(user) {
    return await axios.delete(`users/${user.id}/delete`,{ headers: authHeader(), data: user });
  }
  

  getAdminBoard() {
    //return axios.get(API_URL + 'administrators', { headers: authHeader() });
  }
}

export default new UserService();