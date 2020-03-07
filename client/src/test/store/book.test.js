import Vuex from 'vuex'
import * as bookStore from '@/store/book'
import { createLocalVue } from '@vue/test-utils'
import axios from 'axios'
import clonedeep from 'lodash.clonedeep'

const localVue = createLocalVue()
localVue.use(Vuex)

jest.mock('axios')

describe('store/book.js', () => {
  let store
  let book

  beforeEach(() => {
    store = new Vuex.Store(clonedeep(bookStore))
    book = clonedeep({
      book_id: 1,
      user_id: 1,
      book_title: 'モック用の問題集のタイトルです',
      book_desc: 'モック用の問題集の説明文です。',
      book_is_publish: true,
      book_username_created_by: 'mockuser',
      book_is_favorite: false,
      book_favorite_count: 10,
      book_created_at: '2020-03-07 13:43:01',
      book_updated_at: '2020-03-07 13:43:01'
    })
  })

  describe('mutations', () => {
    test('setBooks を commit すると books ステートに books がセットされる', () => {
      expect(store.state.books).toEqual([])
      store.commit('setBooks', [book])
      expect(store.state.books).toEqual([book])
    })

    test('addBooks を commit すると books ステートに books が追加される', () => {
      expect(store.state.books).toEqual([])
      store.commit('addBooks', [book])
      expect(store.state.books).toEqual([book])
      store.commit('addBooks', [book])
      expect(store.state.books).toEqual([book, book])
      store.commit('addBooks', [book])
      expect(store.state.books).toEqual([book, book, book])
    })

    test('setPage を commit すると currentPage と lastPage ステートに page がセットされる', () => {
      expect(store.state.currentPage).toEqual(0)
      expect(store.state.lastPage).toEqual(null)
      store.commit('setPage', { currentPage: 1, lastPage: 8 })
      expect(store.state.currentPage).toEqual(1)
      expect(store.state.lastPage).toEqual(8)
    })

    test('setBook を commit すると book ステートに book がセットされる', () => {
      expect(store.state.book).toEqual(null)
      store.commit('setBook', book)
      expect(store.state.book).toEqual(book)
    })

    test('setFavorite を commit すると指定した book の favorite が true になる', () => {
      const book1 = clonedeep(book)
      const book2 = clonedeep(book)
      const book3 = clonedeep(book)
      book1.book_id = 1
      book2.book_id = 2
      book3.book_id = 3
      store.commit('setBooks', [book1, book2, book3])
      expect(store.state.books).toEqual([book1, book2, book3])
      const target = store.state.books.find(book => book.book_id === 2)
      expect(target.book_is_favorite).toEqual(false)
      store.commit('setFavorite', { bookId: 2, wiiBeFavorite: true })
      expect(target.book_is_favorite).toEqual(true)
    })

    test('setFavorite を commit すると指定した book の favorite_count が +1 になる', () => {
      const book1 = clonedeep(book)
      const book2 = clonedeep(book)
      const book3 = clonedeep(book)
      book1.book_id = 1
      book2.book_id = 2
      book3.book_id = 3
      store.commit('setBooks', [book1, book2, book3])
      expect(store.state.books).toEqual([book1, book2, book3])
      const target = store.state.books.find(book => book.book_id === 2)
      expect(target.book_favorite_count).toEqual(10)
      store.commit('setFavorite', { bookId: 2, wiiBeFavorite: true })
      expect(target.book_favorite_count).toEqual(11)
    })

    test('setFavorite を commit すると book ステートの favorite が true になる', () => {
      store.commit('setBook', book)
      expect(store.state.book.book_is_favorite).toEqual(false)
      store.commit('setFavorite', { bookId: 1, wiiBeFavorite: true })
      expect(store.state.book.book_is_favorite).toEqual(true)
    })
  })

  describe('actions', () => {
    beforeEach(() => {
      store.$axios = axios
    })

    describe('fetchBooks を dispatch する', () => {
      beforeEach(() => {
        const response = {
          status: 200,
          data: {
            data: [book, book, book],
            current_page: 1,
            last_page: 9
          }
        }
        axios.get.mockResolvedValue(response)
      })

      test('books ステートに books がセットされる', async () => {
        expect(store.state.books).toEqual([])
        await store.dispatch('fetchBooks', { group: 'mines', s: 'keyword' })
        expect(store.state.books).toEqual([book, book, book])
      })

      test('currentPage と lastPage ステートに 各 page がセットされる', async () => {
        expect(store.state.currentPage).toEqual(0)
        expect(store.state.lastPage).toEqual(null)
        await store.dispatch('fetchBooks', { group: 'mines', s: 'keyword' })
        expect(store.state.currentPage).toEqual(1)
        expect(store.state.lastPage).toEqual(9)
      })

      test('status code 200 が return される', async () => {
        const returnStatus = await store.dispatch('fetchBooks', { group: 'mines', s: 'keyword' })
        expect(returnStatus).toEqual(200)
      })
    })

    describe('fetchBook を dispatch する', () => {
      beforeEach(() => {
        const response = {
          status: 200,
          data: { book: book }
        }
        axios.get.mockResolvedValue(response)
      })

      test('book ステートに book がセットされる', async () => {
        expect(store.state.book).toEqual(null)
        await store.dispatch('fetchBook', { bookId: 1 })
        expect(store.state.book).toEqual(book)
      })

      test('status code 200 が return される', async () => {
        const returnStatus = await store.dispatch('fetchBook', { bookId: 1 })
        expect(returnStatus).toEqual(200)
      })
    })

    describe('storeBook を dispatch する', () => {
      beforeEach(() => {
        const response = {
          status: 200,
          data: { book: book }
        }
        axios.post.mockResolvedValue(response)
      })

      test('book ステートに book がセットされる', async () => {
        expect(store.state.book).toEqual(null)
        await store.dispatch('storeBook', {
          title: book.book_title,
          desc: book.book_desc,
          isPublish: book.book_is_publish
        })
        expect(store.state.book).toEqual(book)
      })

      test('status code 200 が return される', async () => {
        const returnStatus = await store.dispatch('storeBook', {
          title: book.book_title,
          desc: book.book_desc,
          isPublish: book.book_is_publish
        })
        expect(returnStatus).toEqual(200)
      })
    })

    describe('updateBook を dispatch する', () => {
      beforeEach(() => {
        const response = {
          status: 200,
          data: { book: book }
        }
        axios.put.mockResolvedValue(response)
      })

      test('book ステートに book がセットされる', async () => {
        expect(store.state.book).toEqual(null)
        await store.dispatch('updateBook', {
          bookId: 1,
          title: book.book_title,
          desc: book.book_desc,
          isPublish: book.book_is_publish
        })
        expect(store.state.book).toEqual(book)
      })

      test('status code 200 が return される', async () => {
        const returnStatus = await store.dispatch('updateBook', {
          bookId: 1,
          title: book.book_title,
          desc: book.book_desc,
          isPublish: book.book_is_publish
        })
        expect(returnStatus).toEqual(200)
      })
    })

    describe('deleteBook を dispatch する', () => {
      beforeEach(() => {
        const response = { status: 200 }
        axios.delete.mockResolvedValue(response)
      })

      test('status code 200 が return される', async () => {
        const returnStatus =
          await store.dispatch('deleteBook', { bookId: 1 })
        expect(returnStatus).toEqual(200)
      })
    })

    describe('storeFavorite を dispatch する', () => {
      beforeEach(() => {
        const book1 = clonedeep(book)
        const book2 = clonedeep(book)
        const book3 = clonedeep(book)
        book1.book_id = 1
        book2.book_id = 2
        book3.book_id = 3
        store.commit('setBooks', [book1, book2, book3])
        store.commit('setBook', clonedeep(book2))
      })

      test('books ステートの指定 book の book_is_favorite が true になる', async () => {
        const targetBookId = 2
        const target = store.state.books.find(book => book.book_id === targetBookId)
        expect(target.book_is_favorite).toEqual(false)
        expect(store.state.book.book_is_favorite).toEqual(false)
        await store.dispatch('storeFavorite', { bookId: targetBookId })
        expect(target.book_is_favorite).toEqual(true)
        expect(store.state.book.book_is_favorite).toEqual(true)
      })

      test('books ステートの指定 book の book_favorite_count が +1 になる', async () => {
        const targetBookId = 2
        const target = store.state.books.find(book => book.book_id === targetBookId)
        expect(target.book_favorite_count).toEqual(10)
        expect(store.state.book.book_favorite_count).toEqual(10)
        await store.dispatch('storeFavorite', { bookId: targetBookId })
        expect(target.book_favorite_count).toEqual(11)
        expect(store.state.book.book_favorite_count).toEqual(11)
      })
    })

    describe('deleteFavorite を dispatch する', () => {
      beforeEach(() => {
        const book1 = clonedeep(book)
        const book2 = clonedeep(book)
        const book3 = clonedeep(book)
        book1.book_id = 1
        book2.book_id = 2
        book3.book_id = 3
        book1.book_is_favorite = true
        book2.book_is_favorite = true
        book3.book_is_favorite = true
        store.commit('setBooks', [book1, book2, book3])
        store.commit('setBook', clonedeep(book2))
      })

      test('books ステートの指定 book の book_is_favorite が false になる', async () => {
        const targetBookId = 2
        const target = store.state.books.find(book => book.book_id === targetBookId)
        expect(target.book_is_favorite).toEqual(true)
        expect(store.state.book.book_is_favorite).toEqual(true)
        await store.dispatch('deleteFavorite', { bookId: targetBookId })
        expect(target.book_is_favorite).toEqual(false)
        expect(store.state.book.book_is_favorite).toEqual(false)
      })

      test('books ステートの指定 book の book_favorite_count が -1 になる', async () => {
        const targetBookId = 2
        const target = store.state.books.find(book => book.book_id === targetBookId)
        expect(target.book_favorite_count).toEqual(10)
        expect(store.state.book.book_favorite_count).toEqual(10)
        await store.dispatch('deleteFavorite', { bookId: targetBookId })
        expect(target.book_favorite_count).toEqual(9)
        expect(store.state.book.book_favorite_count).toEqual(9)
      })
    })
  })
})
