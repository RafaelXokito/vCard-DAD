import axios from "axios";
import authHeader from "./auth-header";

class PaymentTypeService {
  async getPaymentTypeBoard(link = `payment_types`) {
    return await axios.get(link, { headers: authHeader() });
  }
  async getPaymentType(link = `payment_types`) {
    return await axios.get(link, { headers: authHeader() });
  }
  postPaymenttype(paymenttype){
    return axios.post('payment_types',paymenttype, { headers: authHeader() });
  }

  patchPaymentType(paymenttype){
    return axios.patch(`payment_types/${paymenttype.code}`,paymenttype, { headers: authHeader() });
  }

  deletePaymenttype(paymenttype){
    return axios.delete(`payment_types/${paymenttype.code}`, { headers: authHeader() });
  }
}

export default new PaymentTypeService();
