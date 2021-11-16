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

  register(user) {
    return axios.post('registerVCard', {
      phone_number: user.phone_number,
      name: user.name,
      email: user.email,
      photo_url: user.photo_url,
      password: user.password,
      confirmation_code: user.confirmation_code,
    })
    .then(response => {
      if (response.data.user) {
        localStorage.setItem('user', JSON.stringify(response.data.user));
      }
      return response.data.user;
    });
  }

  async confirmationCode(user) {
    let promise = await axios.post('users/'+ user.id +'/confirmationCode', {"confirmationCode": user.confirmationCode}, { headers: authHeader() })
      .then(() => {
        user = JSON.parse(localStorage.getItem('user'));
        user['confirmationCode'] = true;
        localStorage.setItem('user', JSON.stringify(user));
      });
    return promise;
  }
}

export default new AuthService();