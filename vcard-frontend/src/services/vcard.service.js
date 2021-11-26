import axios from 'axios';
import authHeader from './auth-header';

class VCardService {
  async getVCard(phone_number, link=`vcards/${phone_number}`) {
    let promise = await axios.get(link, { headers: authHeader() }).then(({data})=> {
        let user = JSON.parse(localStorage.getItem('user'));
        user['balance'] = data.data.balance;
        localStorage.setItem('user', JSON.stringify(user));
        return user;
    });
    return promise; 
  }
  async makeConfirmationPhoneNumber(user){
    let promise = await axios.get('vards/'+ user.username +'/makeConfirmationPhoneNumber', { headers: authHeader() })
      .then(() => {
        return promise; 
      });
      return user; 
  }
  async verifyConfirmationPhoneNumber(user){
    console.log(user.code);
    let promise = await axios.post('vards/'+ user.username +'/verifyConfirmationPhoneNumber',{"code": user.code}, { headers: authHeader() })
      .then(() => {
        return promise; 
      });
      return user; 
  }

  async closeConfirmationPhoneNumber(user){
    let promise = await axios.get('vards/'+ user.username +'/closeConfirmationPhoneNumber', { headers: authHeader() })
      .then(() => {
        return promise; 
      });
      return user; 
  }

  async checkConfirmationPhoneNumber(user){
    let promise = await axios.get('vards/'+ user.username +'/checkConfirmationPhoneNumber', { headers: authHeader() })
      .then(() => {
        return promise; 
      });
      return user; 
  }
}

export default new VCardService();