import axios from 'axios';

const API_URL = 'http://localhost/api/';

class AuthService {
  login(user) {
    let bodyFormData = new FormData()
    bodyFormData.set('username', user.username)
    bodyFormData.set('password', user.password)
    return axios
      .post(API_URL + 'signin', bodyFormData)
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
    return axios.post(API_URL + 'signup', {
      username: user.username,
      email: user.email,
      password: user.password
    });
  }
}

export default new AuthService();