import axios from 'axios';

const API_URL = 'http://localhost/api/';
const PASSPORT_SERVER_URL = "http://localhost"
const CLIENT_ID = 2
const CLIENT_SECRET = 'gLptW4CUN5LQOLOiZYw2EcAchleK1ORMYMuDfvvV'

class AuthService {
  login(user) {
    let bodyFormData = new FormData()

    bodyFormData.set('grant_type', 'password')
    bodyFormData.set('client_id', CLIENT_ID)
    bodyFormData.set('client_secret', CLIENT_SECRET)
    bodyFormData.set('scope', "")
    bodyFormData.set('username', user.username)
    bodyFormData.set('password', user.password)
    console.log(user);
    return axios
      .post(PASSPORT_SERVER_URL + '/oauth/token', bodyFormData)
      .then(response => {
        if (response.data.access_token) {
          localStorage.setItem('user', JSON.stringify(response.data.user));
        }

        return response.data;
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