export default {
  state : {
    user : {
      login : '',
      email : '',
      fullName : '',
    },
  },
  mutations : {
    setLogin(state, newLogin){
      state.user.login = newLogin
    },
    setFullName(state, newFullName){
      state.user.fullName = newFullName
    },
    setEmail(state, newEmail){
      state.user.email = newEmail
    },
  },
  actions : {},
  getters : {
    user(state){
      return state.user;
    },
  },
}