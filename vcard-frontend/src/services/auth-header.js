export default function authHeader(ContentType = null) {
    let user = JSON.parse(localStorage.getItem('user'));
    
    if (ContentType != null && user && user.access_token) {
      return { Authorization: user.token_type + ' ' + user.access_token, "Content-Type": ContentType };
    } else if (user && user.access_token) {
      return { Authorization: user.token_type + ' ' + user.access_token };
    } else {
      return {};
    }
  }