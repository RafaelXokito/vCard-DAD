import axios from 'axios';
import authHeader from './auth-header';

class AuthService {
  login(user) {
    let bodyFormData = new FormData()
    bodyFormData.set('username', user.username)
    bodyFormData.set('password', user.password)
    return axios
      .post('signin', bodyFormData)
      .then(response => {
        if (response.data.user) {
          localStorage.setItem('user', JSON.stringify(response.data.user));
        }
        return response.data.user;
      });
  }

  logout() {
    localStorage.removeItem('user');
  }

  register(form_data) {
    return axios.post('registerVCard', form_data)
    .then(response => {
      if (response.data.user) {
        localStorage.setItem('user', JSON.stringify(response.data.user));
      }
      return response.data.user;
    });
  }

  async confirmationCode(user) {
    let promise = await axios.post('vards/'+ user.username +'/confirmationCode', {"confirmationCode": user.confirmationCode}, { headers: authHeader() })
      .then(() => {
        return promise; 
      });
      return user; 
  }
}

export default new AuthService();