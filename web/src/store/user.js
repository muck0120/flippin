import Cookies from 'js-cookie'

export const state = () => ({
  token: null,
  user: null
})

export const mutations = {
  setToken (state, token) {
    state.token = token
  },
  setUser (state, user) {
    state.user = user
  }
}

export const actions = {
  async signup ({ commit }, { name, mail, password }) {
    try {
      const { status, data: { token, user } } =
        await this.$axios.post('/users/profile', {
          user_name: name,
          user_mail: mail,
          user_password: password
        })
      Cookies.set('token', token)
      commit('setToken', token)
      commit('setUser', user)
      return status
    } catch (e) {
      return e.response.status
    }
  },
  async update ({ commit }, { name, mail, password }) {
    try {
      const { status, data: { user } } =
        await this.$axios.put('/users/profile', {
          user_name: name,
          user_mail: mail,
          user_password: password
        })
      commit('setUser', user)
      return status
    } catch (e) {
      return e.response.status
    }
  },
  async login ({ commit }, { mail, password }) {
    try {
      const { status, data: { token, user } } =
        await this.$axios.post('/login', {
          user_mail: mail,
          user_password: password
        })
      Cookies.set('token', token)
      commit('setToken', token)
      commit('setUser', user)
      return status
    } catch (e) {
      return e.response.status
    }
  },
  async logout ({ commit }) {
    try {
      const { status } = await this.$axios.get('/logout')
      Cookies.remove('token')
      commit('setToken', null)
      commit('setUser', null)
      return status
    } catch (e) {
      return e.response.status
    }
  }
}
