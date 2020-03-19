<template>
  <div :class="$style.wrap">
    <nav :class="$style.menus">
      <NLink
        :to="{ path: '/books', query: { tab: 'mines' } }"
        :class="[$style.menu, { [$style.active]: tabIsMines }]"
      >
        MY問題集
      </NLink>
      <NLink
        :to="{ path: '/books', query: { tab: 'others' } }"
        :class="[$style.menu, { [$style.active]: tabIsOthers }]"
      >
        みんなの問題集
      </NLink>
      <NLink
        :to="{ path: '/books', query: { tab: 'favorites' } }"
        :class="[$style.menu, { [$style.active]: tabIsFavorites }]"
      >
        お気に入り
      </NLink>
    </nav>
    <div :class="$style.search">
      <input
        v-model="search"
        type="text"
        placeholder="検索ワード"
        :class="$style.search__input"
      >
      <NLink
        :to="{ path: '/books', query: { tab: $route.query.tab, s: search } }"
        tag="button"
        :class="$style.search__button"
      >
        検索
      </NLink>
    </div>
    <div :class="$style.books">
      <template v-if="books.length > 0">
        <div
          v-for="book in books"
          :key="`${book.book_id}`"
          :class="$style.card"
        >
          <ListBook
            :book="book"
            @requiredLogin="isOpenModalLogin = true"
          />
        </div>
        <client-only>
          <InfiniteLoading
            @infinite="infiniteHandler"
            :class="$style.infiniteloading"
          >
            <div slot="spinner">
              <Spiner />
            </div>
            <div slot="no-more">
              <!-- No More Data -->
            </div>
          </InfiniteLoading>
        </client-only>
      </template>
      <template v-else-if="!tabIsFavorites">
        <NoBook />
      </template>
      <template v-else-if="tabIsFavorites">
        <NoFavorite />
      </template>
    </div>
    <NLink
      to="/books/create"
      :class="$style.add"
    >
      新規問題集作成
      <fa
        :icon="faPlus"
        :class="$style.plus"
      />
    </NLink>
    <!-- 通信エラー時のモーダル -->
    <Modal
      v-if="isOpenModalError"
      @close="isOpenModalError = false"
    >
      <template #title>
        通信エラー
      </template>
      <template #desc>
        エラーが発生しました。もう一度お試し下さい。
      </template>
      <template #button>
        <ModalButtonOne @proceed="isOpenModalError = false" />
      </template>
    </Modal>
    <!-- ログイン要求のモーダル -->
    <Modal
      v-if="isOpenModalLogin"
      @close="isOpenModalLogin = false"
    >
      <template #title>
        ログインが必要です
      </template>
      <template #desc>
        お気に入りに登録するにはログインが必要です。<br>
        ログイン画面へ移動しますか？
      </template>
      <template #button>
        <ModalButtonTwo
          label-proceed="ログイン"
          label-cancel="キャンセル"
          @proceed="$router.push('/signin')"
          @cancel="isOpenModalLogin = false"
        />
      </template>
    </Modal>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import { faPlus } from '@fortawesome/free-solid-svg-icons'
import ListBook from '@/components/ListBook.vue'
import NoBook from '@/components/TheNoBook.vue'
import NoFavorite from '@/components/TheNoFavorite.vue'
import Spiner from '@/components/InfiniteLoadingSpiner.vue'
import Modal from '@/components/Modal'
import ModalButtonOne from '@/components/ModalButtonOne'
import ModalButtonTwo from '@/components/ModalButtonTwo'

export default {
  validate ({ query }) {
    if (!query.tab) return true
    return /(others|mines|favorites)/.test(query.tab)
  },
  async asyncData ({ store, query }) {
    store.commit('book/setPage', { currentPage: 0, lastPage: null })
    store.commit('book/setBooks', [])
    const status = await store.dispatch('book/fetchBooks', {
      group: query.tab || 'others',
      s: query.s || null
    })
    return {
      search: query.s,
      isOpenModalError: status !== 200,
      isOpenModalLogin: false
    }
  },
  head () {
    return {
      title: '問題集一覧'
    }
  },
  components: {
    ListBook,
    NoBook,
    NoFavorite,
    Spiner,
    Modal,
    ModalButtonOne,
    ModalButtonTwo
  },
  watchQuery: true,
  computed: {
    faPlus () {
      return faPlus
    },
    tabIsMines () {
      return this.$route.query.tab === 'mines'
    },
    tabIsOthers () {
      return !this.tabIsMines && !this.tabIsFavorites
    },
    tabIsFavorites () {
      return this.$route.query.tab === 'favorites'
    },
    ...mapState('book', ['books', 'currentPage', 'lastPage'])
  },
  methods: {
    async infiniteHandler ($state) {
      await this.$store.dispatch('book/fetchBooks', {
        group: this.$route.query.tab || 'others',
        s: this.$route.query.s || null
      })
      $state.loaded()
      if (this.currentPage >= this.lastPage) {
        $state.complete()
      }
    }
  }
}
</script>

<style lang="scss" module>
$menu-height-pc: 50px;
$menu-height-tb: 45px;
$menu-height-sp: 40px;

.wrap {
  width: 100%;
  height: 100%;
}

.menus {
  width: 100%;
  height: $menu-height-pc;
  border-radius: $menu-height-pc / 2;
  background-color: #fff;
  border: 3px solid #00b8a9;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);

  @include mq(tb) {
    height: $menu-height-tb;
    border-radius: $menu-height-tb / 2;
  }

  @include mq(sp) {
    height: $menu-height-sp;
    border-radius: $menu-height-sp / 2;
  }
}

.menu {
  width: calc(100% / 3);
  height: $menu-height-pc;
  border-radius: $menu-height-pc / 2;
  display: flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;
  font-size: 16px;
  font-weight: bold;
  white-space: nowrap;
  transition: all 0.3s;

  @include mq(tb) {
    font-size: 14px;
    height: $menu-height-tb;
    border-radius: $menu-height-tb / 2;
  }

  @include mq(sp) {
    font-size: 12px;
    height: $menu-height-sp;
    border-radius: $menu-height-sp / 2;
  }
}

.menu.active {
  background-color: #00b8a9;
  color: #fff;
  transition: all 0.3s;
  pointer-events: none;
}

.search {
  margin-top: 30px;
  height: $menu-height-pc;
  display: flex;
  justify-content: space-between;
  align-items: center;

  @include mq(tb) {
    margin-top: 25px;
    height: $menu-height-tb;
    border-radius: $menu-height-tb / 2;
  }

  @include mq(sp) {
    margin-top: 20px;
    height: $menu-height-sp;
    border-radius: $menu-height-sp / 2;
  }
}

.search__input {
  width: calc(100% / 3 * 2);
  height: $menu-height-pc;
  border-radius: $menu-height-pc / 2;
  background-color: #fff;
  border: 3px solid #00b8a9;
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);
  padding: 0 20px;

  @include mq(tb) {
    height: $menu-height-tb;
    border-radius: $menu-height-tb / 2;
  }

  @include mq(sp) {
    height: $menu-height-sp;
    border-radius: $menu-height-sp / 2;
  }
}

.search__input::placeholder {
  color: #999;
  font-size: 16px;

  @include mq(tb) {
    font-size: 14px;
  }

  @include mq(sp) {
    font-size: 12px;
  }
}

.search__button {
  width: calc(100% / 3 - 20px);
  height: $menu-height-pc;
  border-radius: $menu-height-pc / 2;
  background-color: #00b8a9;
  color: #fff;
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);
  font-size: 16px;
  font-weight: bold;
  transition: all 0.3s;

  @include mq(tb) {
    font-size: 14px;
    width: calc(100% / 3 - 15px);
    height: $menu-height-tb;
    border-radius: $menu-height-tb / 2;
  }

  @include mq(sp) {
    font-size: 12px;
    width: calc(100% / 3 - 10px);
    height: $menu-height-sp;
    border-radius: $menu-height-sp / 2;
  }
}

.search__button:hover {
  box-shadow: none;
  transition: all 0.3s;
}

.books {
  margin-top: 50px;
  display: flex;
  justify-content: flex-start;
  align-items: stretch;
  flex-wrap: wrap;

  @include mq(tb) {
    margin-top: 40px;
  }

  @include mq(sp) {
    margin-top: 30px;
  }
}

.card {
  width: calc((100% - 40px) / 3);
  margin: 0 0 20px 20px;

  @include mq(tb) {
    width: calc((100% - 20px) / 2);
  }

  @include mq(sp) {
    width: 100%;
    margin: 0 0 20px 0;
  }
}

.card:nth-of-type(3n + 1) {
  margin: 0 0 20px 0;

  @include mq(tb) {
    margin: 0 0 20px 20px;
  }

  @include mq(sp) {
    margin: 0 0 20px 0;
  }
}

.card:nth-of-type(2n + 1) {
  @include mq(tb) {
    margin: 0 0 20px 0;
  }

  @include mq(sp) {
    margin: 0 0 20px 0;
  }
}

.infiniteloading {
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.add {
  $height-pc: 50px;
  $height-tb: 45px;
  $height-sp: 40px;

  display: flex;
  justify-content: center;
  align-items: center;
  width: 190px;
  height: $height-pc;
  border: 3px solid #00b8a9;
  border-radius: $height-pc / 2;
  background-color: #fff;
  color: #00b8a9;
  font-size: 16px;
  font-weight: bold;
  white-space: nowrap;
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);
  cursor: pointer;
  transition: all 0.3s;
  position: fixed;
  bottom: 20px;
  right: 20px;

  @include mq(tb) {
    width: 170px;
    height: $height-tb;
    border-radius: $height-tb / 2;
    font-size: 14px;
  }

  @include mq(sp) {
    width: 150px;
    height: $height-sp;
    border-radius: $height-sp / 2;
    font-size: 12px;
  }
}

.add:hover {
  box-shadow: none;
  transition: all 0.3s;
}

.plus {
  margin-left: 5px;
  transition: all 0.3s;
}

.add:hover .plus {
  transform: rotate(90deg);
  transition: all 0.3s;
}
</style>
