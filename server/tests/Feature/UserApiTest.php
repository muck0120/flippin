<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $password = 'password';

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * エンドポイント /unauthorized が status 401 を return することをテスト。
     *
     * @return void
     */
    public function testUnauthorizedUri()
    {
        $this->get('/unauthorized')
            ->assertStatus(401)
            ->assertExactJson(['message' => 'Unauthorized.']);
    }

    /**
     * エンドポイント /notfound が status 404 を return することをテスト。
     *
     * @return void
     */
    public function testNotFoundUri()
    {
        $this->get('/notfound')
            ->assertStatus(404)
            ->assertExactJson(['message' => 'Not found.']);
    }

    /**
     * ログインが成功することをテスト。
     *
     * @return void
     */
    public function testSuccessLogin()
    {
        $response = $this->postJson('/login', [
            'user_mail' => $this->user->user_mail,
            'user_password' => $this->password
        ]);

        $token = User::find($this->user->user_id)->user_api_token;
        $this->assertNotNull($token);

        $response->assertStatus(200)
            ->assertJson([
                'token' => $token,
                'user' => [
                    'user_id' => $this->user->user_id,
                    'user_name' => $this->user->user_name,
                    'user_mail' => $this->user->user_mail
                ]
            ]);

        $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->getJson('/users/profile')
            ->assertStatus(200);
    }

    /**
     * ログインが失敗することをテスト。
     *
     * @param string $mail 認証用メールアドレス
     * @param string $password 認証用パスワード
     * @return void
     */
    public function testFailLogin()
    {
        $this->postJson('/login', [
            'user_mail' => $this->user->user_mail,
            'user_password' => 'wrongpassword',
        ])->assertRedirect('/unauthorized');

        $token = User::find($this->user->user_id)->user_api_token;
        $this->assertNull($token);

        $this->postJson('/login', [
            'user_mail' => 'wrong@example.com',
            'user_password' => $this->password,
        ])->assertRedirect('/unauthorized');
    }

    /**
     * ログアウトが成功することをテスト。
     *
     * @return void
     */
    public function testSuccessLogout()
    {
        $this->testSuccessLogin();

        $token = User::find($this->user->user_id)->user_api_token;

        $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->get('/logout')
            ->assertStatus(200);

        $token = User::find($this->user->user_id)->user_api_token;
        $this->assertNull($token);
    }

    /**
     * ログイン中で無ければログアウトが失敗することをテスト。
     *
     * @return void
     */
    public function testFailLogout()
    {
        $token = User::find($this->user->user_id)->user_api_token;
        $this->assertNull($token);

        $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->get('/logout')
            ->assertRedirect('/unauthorized');
    }

    /**
     * ログイン中のユーザーを取得できることをテスト。
     *
     * @return void
     */
    public function testSuccessGetUserProfile()
    {
        $this->testSuccessLogin();

        $token = User::find($this->user->user_id)->user_api_token;
        $this->assertNotNull($token);

        $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->getJson('/users/profile')
            ->assertStatus(200)
            ->assertJson([
                'user_id' => $this->user->user_id,
                'user_name' => $this->user->user_name,
                'user_mail' => $this->user->user_mail
            ]);
    }

    /**
     * ログイン中で無ければユーザーを取得できないことをテスト。
     *
     * @return void
     */
    public function testFailGetUserProfile()
    {
        $token = User::find($this->user->user_id)->user_api_token;
        $this->assertNull($token);

        $this->withHeaders(['Authorization' => 'Bearer '.$token])
            ->getJson('/users/profile')
            ->assertStatus(401);
    }

    /**
     * ユーザーの新規作成が成功することをテスト。
     *
     * @return void
     */
    public function testSuccessCreateUser()
    {
        $newUser = factory(User::class)->make();

        $response = $this->postJson('/users/profile', [
            'user_name' => $newUser->user_name,
            'user_mail' => $newUser->user_mail,
            'user_password' => 'password'
        ]);

        $token = User::where([
            'user_name' => $newUser->user_name,
            'user_mail' => $newUser->user_mail
        ])->first()->user_api_token;

        $this->assertNotNull($token);

        $this->assertDatabaseHas('users', [
            'user_name' => $newUser->user_name,
            'user_mail' => $newUser->user_mail,
            'user_api_token' => $token
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'token' => $token,
                'user' => [
                    'user_name' => $newUser->user_name,
                    'user_mail' => $newUser->user_mail
                ]
            ]);
    }

    /**
     * ユーザーの新規作成が失敗することをテスト（バリデーションのテスト）。
     *
     * @dataProvider dataproviderFailCreateUser
     * @param string $name
     * @param string $mail
     * @param string $password
     * @return void
     */
    public function testFailCreateUser($name, $mail, $password)
    {
        $this->postJson('/users/profile', [
            'user_name' => $name,
            'user_mail' => $mail !== 'checkUnique' ? $mail : $this->user->user_mail,
            'user_password' => $password
        ])->assertStatus(422);
    }

    public function dataproviderFailCreateUser()
    {
        $userName = '12345678901234567890';
        $userMail = 'test@example.com';
        $userPassword = 'password';

        return [
            'user_name なし' => ['', $userMail, $userPassword],
            'user_name 20文字以上' => [$userName.'x', $userMail, $userPassword],
            'user_mail なし' => [$userName, '', $userPassword],
            'user_mail 形式間違い' => [$userName, 'wrongemail', $userPassword],
            'user_mail 同一アドレス' => [$userName, 'checkUnique', $userPassword],
            'user_password なし' => [$userName, $userMail, '']
        ];
    }

    /**
     * ユーザーの情報更新が成功することをテスト。
     *
     * @dataProvider dataproviderSuccessUpdateUser
     * @param string $name
     * @param string $mail
     * @param string $password
     * @return void
     */
    public function testSuccessUpdateUser($name, $mail, $password)
    {
        $response = $this->actingAs($this->user, 'api')
            ->putJson('/users/profile', [
                'user_name' => $name,
                'user_mail' => $mail,
                'user_password' => $password
            ]);

        $this->assertDatabaseHas('users', [
            'user_name' => $name,
            'user_mail' => $mail
        ]);

        $storedPassword = User::find($this->user->user_id)->user_password;

        $password === '' ?
            $this->assertTrue(Hash::check($this->password, $storedPassword)) :
            $this->assertTrue(Hash::check($password, $storedPassword));

        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'user_id' => $this->user->user_id,
                    'user_name' => $name,
                    'user_mail' => $mail
                ]
            ]);
    }

    public function dataproviderSuccessUpdateUser()
    {
        $userName = 'updateuser';
        $userMail = 'update@example.com';
        $userPassword = 'updatepassword';

        return [
            'user_password なし' => [$userName, $userMail, ''],
            'user_password あり' => [$userName, $userMail, $userPassword],
        ];
    }

    /**
     * ユーザーの情報更新が失敗することをテスト（バリデーションのテスト）。
     *
     * @dataProvider dataproviderFailUpdateUser
     * @param string $name
     * @param string $mail
     * @param string $password
     * @return void
     */
    public function testFailUpdateUser($name, $mail, $password)
    {
        $this->actingAs($this->user, 'api')
            ->putJson('/users/profile', [
                'user_name' => $name,
                'user_mail' => $mail,
                'user_password' => $password
            ])
            ->assertStatus(422);

        $this->assertDatabaseMissing('users', [
                'user_name' => $name,
                'user_mail' => $mail
            ]);
    }

    public function dataproviderFailUpdateUser()
    {
        $userName = 'updateuser';
        $userMail = 'update@example.com';
        $userPassword = 'updatepassword';

        return [
            'user_name なし' => ['', $userMail, $userPassword],
            'user_name 20文字以上' => ['123456789012345678901', $userMail, $userPassword],
            'user_mail なし' => [$userName, '', $userPassword],
            'user_mail 形式間違い' => [$userName, 'wrongemail', $userPassword]
        ];
    }
}
