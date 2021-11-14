import axios from 'axios';

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
}

export default new AuthService();