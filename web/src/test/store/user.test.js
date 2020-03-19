import Vuex from 'vuex'
import * as userStore from '@/store/user'
import { createLocalVue } from '@vue/test-utils'
import axios from 'axios'
import Cookies from 'js-cookie'
import clonedeep from 'lodash.clonedeep'

const localVue = createLocalVue()
localVue.use(Vuex)

jest.mock('axios')

describe('store/user.js', () => {
  let store

  const name = 'mockuser'
  const mail = 'mockuser@example.com'
  const password = 'password'
  const token = 'abcdefghijklmnopqrstuvwxyz'
  const status = 200
  const user = {
    user_id: 1,
    user_name: name,
    user_mail: mail,
    user_created_at: '2020-01-01 01:01:01',
    user_updated_at: '2020-01-01 01:01:01'
  }
  const response = {
    status: status,
    data: { token: token, user: user }
  }

  beforeEach(() => {
    store = new Vuex.Store(clonedeep(userStore))
  })

  describe('mutations', () => {
    test('setToken を commit すると token ステートに token がセットされる', () => {
      expect(store.state.token).toBeNull()
      store.commit('setToken', token)
      expect(store.state.token).toEqual(token)
    })

    test('setUser を commit すると user ステートに user がセットされる', () => {
      expect(store.state.user).toBeNull()
      store.commit('setUser', user)
      expect(store.state.user).toEqual(user)
    })
  })

  describe('actions', () => {
    beforeEach(() => {
      store.$axios = axios
    })

    afterEach(() => {
      Cookies.remove('token')
    })

    describe('signup を dispatch する', () => {
      axios.post.mockResolvedValue(response)

      test('token ステートに token がセットされる', async () => {
        expect(store.state.token).toBeNull()
        await store.dispatch('signup', { name, mail, password })
        expect(store.state.token).toEqual(token)
      })

      test('user ステートに user がセットされる', async () => {
        expect(store.state.user).toBeNull()
        await store.dispatch('signup', { name, mail, password })
        expect(store.state.user).toEqual(user)
      })

      test('cookie に token がセットされる', async () => {
        expect(Cookies.get('token')).toBeUndefined()
        await store.dispatch('signup', { name, mail, password })
        expect(Cookies.get('token')).toEqual(token)
      })

      test('status code 200 が return される', async () => {
        const returnStatus = await store.dispatch('signup', { name, mail, password })
        expect(returnStatus).toEqual(status)
      })
    })

    describe('update を dispatch する', () => {
      axios.put.mockResolvedValue(response)

      test('user ステートに user がセットされる', async () => {
        expect(store.state.user).toBeNull()
        await store.dispatch('update', { name, mail, password })
        expect(store.state.user).toEqual(user)
      })

      test('status code 200 が return される', async () => {
        const returnStatus = await store.dispatch('update', { name, mail, password })
        expect(returnStatus).toEqual(status)
      })
    })

    describe('login を dispatch する', () => {
      axios.post.mockResolvedValue(response)

      test('token ステートに token がセットされる', async () => {
        expect(store.state.token).toBeNull()
        await store.dispatch('login', { mail, password })
        expect(store.state.token).toEqual(token)
      })

      test('user ステートに user がセットされる', async () => {
        expect(store.state.user).toBeNull()
        await store.dispatch('login', { mail, password })
        expect(store.state.user).toEqual(user)
      })

      test('cookie に token がセットされる', async () => {
        expect(Cookies.get('token')).toBeUndefined()
        await store.dispatch('login', { mail, password })
        expect(Cookies.get('token')).toEqual(token)
      })

      test('status code 200 が return される', async () => {
        const returnStatus = await store.dispatch('login', { mail, password })
        expect(returnStatus).toEqual(status)
      })
    })

    describe('logout を dispatch する', () => {
      axios.get.mockResolvedValue({ status })

      test('token が cookie から消去される', async () => {
        Cookies.set('token', token)
        expect(Cookies.get('token')).toEqual(token)
        await store.dispatch('logout')
        expect(Cookies.get('token')).toBeUndefined()
      })

      test('token ステートに null がセットされる', async () => {
        store.commit('setToken', token)
        expect(store.state.token).toEqual(token)
        await store.dispatch('logout')
        expect(store.state.token).toBeNull()
      })

      test('user ステートに null がセットされる', async () => {
        store.commit('setUser', user)
        expect(store.state.user).toEqual(user)
        await store.dispatch('logout')
        expect(store.state.user).toBeNull()
      })

      test('status code 200 が return される', async () => {
        const returnStatus = await store.dispatch('logout')
        expect(returnStatus).toEqual(status)
      })
    })
  })
})
