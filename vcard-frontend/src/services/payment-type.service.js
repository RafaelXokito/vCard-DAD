import axios from 'axios';
import authHeader from './auth-header';

class PaymentTypeService {
  async getPaymentTypeBoard(link=`payment_types`) {
    return await axios.get(link, { headers: authHeader() });
  }
}

export default new PaymentTypeService();