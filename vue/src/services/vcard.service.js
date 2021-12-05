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
    let promise = await axios.get('vards/'+ user.phoneNumber +'/makeConfirmationPhoneNumber', { headers: authHeader() })
      .then(() => {
        return promise; 
      });
      return user; 
  }
  async verifyConfirmationPhoneNumber(user){
    let promise = await axios.post('vards/'+ user.phoneNumber +'/verifyConfirmationPhoneNumber',{"code": user.code}, { headers: authHeader() })
      .then(() => {
        return promise; 
      });
      return user; 
  }

  async blockVcard(user){
    return await axios.patch(`vcards/${user.id ?? user.username}/block`,{}, { headers: authHeader() });
  }

  async changeMaxDebit(user){
    return await axios.patch(`vcards/${user.id ?? user.username}/changeMaxDebit`,user, { headers: authHeader() });
  }

  async delete(user) {
    return await axios.delete(`vcards/${user.username}/delete`,{ headers: authHeader(), data: user });
    //return await axios.delete(`vcards/${user.username}/delete`, { headers: authHeader() }, {data: user});
  }

  async restoreVCard(user) {
    return await axios.post(`vcards/${user.username}/restorevcard`,user,{ headers: authHeader() });
  }

  async closeConfirmationPhoneNumber(user){
    let promise = await axios.get('vards/'+ user.phoneNumber +'/closeConfirmationPhoneNumber', { headers: authHeader() })
      .then(() => {
        return promise; 
      });
      return user; 
  }

  async checkConfirmationPhoneNumber(user){
    let promise = await axios.get('vards/'+ user.phoneNumber +'/checkConfirmationPhoneNumber', { headers: authHeader() })
      .then(() => {
        return promise; 
      });
      return user; 
  }
}

export default new VCardService();