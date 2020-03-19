<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Card;
use App\Models\CardChoice;

class CardApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $book;
    protected $card;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->user = factory(User::class)->create();
        $this->book = factory(Book::class)->create([
            'user_id' => $this->user->user_id
        ]);
        $this->card = factory(Card::class)->create([
            'book_id' => $this->book->book_id,
            'card_order' => Card::where('book_id', $this->book->book_id)
                ->max('card_order') + 1
        ]);
    }

    /**
     * 問題の新規作成ができることをテスト。
     *
     * @return void
     */
    public function testSuccessCreateCard()
    {
        $createCard = factory(Card::class)->make();
        $createCardChoice1 = factory(CardChoice::class)->make([
            'card_choice_is_correct' => true
        ]);
        $createCardChoice2 = factory(CardChoice::class)->make();
        $questionImage = UploadedFile::fake()->image('question.jpg');
        $explanationImage = UploadedFile::fake()->image('explanation.jpg');

        $this->actingAs($this->user, 'api')
            ->postJson('/books'.'/'.$this->book->book_id.'/cards/card', [
                'card_question' => $createCard->card_question,
                'card_question_image' => $questionImage,
                'card_choices' => [$createCardChoice1, $createCardChoice2],
                'card_explanation' => $createCard->card_explanation,
                'card_explanation_image' => $explanationImage
            ])->assertStatus(200);

        $this->assertDatabaseHas('cards', [
            'book_id' => $this->book->book_id,
            'card_question' => $createCard->card_question,
            'card_explanation' => $createCard->card_explanation
        ]);
        $this->assertDatabaseHas('card_choices', [
            'card_choice_text' => $createCardChoice1->card_choice_text,
            'card_choice_is_correct' => $createCardChoice1->card_choice_is_correct
        ]);
        $this->assertDatabaseHas('card_choices', [
            'card_choice_text' => $createCardChoice2->card_choice_text,
            'card_choice_is_correct' => $createCardChoice2->card_choice_is_correct
        ]);

        $storedCard = Card::where([
            'book_id' => $this->book->book_id,
            'card_question' => $createCard->card_question,
            'card_explanation' => $createCard->card_explanation
        ])->first();

        $this->assertEquals(
            $storedCard->card_order,
            Card::where('book_id', $this->book->book_id)->max('card_order')
        );

        $imageBasePath = 'public/images/cards/'.$storedCard->card_id;
        $imageQuestionPath = $imageBasePath.'/'.$storedCard->card_question_image;
        $imageExplanationPath = $imageBasePath.'/'.$storedCard->card_explanation_image;
        Storage::disk('local')->assertExists($imageQuestionPath);
        Storage::disk('local')->assertExists($imageExplanationPath);
    }

    /**
     * 問題の新規作成が失敗することをテスト（バリデーションのテスト）。
     *
     * @dataProvider dataproviderFailCreateCard
     * @param string $question
     * @param array $choices
     * @param string $explanation
     * @return void
     */
    public function testFailCreateCard($question, $choices, $explanation)
    {
        $this->actingAs($this->user, 'api')
            ->postJson('/books'.'/'.$this->book->book_id.'/cards/card', [
                'card_question' => $question,
                'card_question_image' => null,
                'card_choices' => $choices,
                'card_explanation' => $explanation,
                'card_explanation_image' => null
            ])->assertStatus(422);
    }

    public function dataproviderFailCreateCard()
    {
        return [
            'card_question なし' => [
                '',
                [
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => true
                    ],
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => false
                    ]
                ],
                str_repeat('a', 2000)
            ],
            'card_question 2000文字以上' => [
                str_repeat('a', 2001),
                [
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => true
                    ],
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => false
                    ]
                ],
                str_repeat('a', 2000)
            ],
            'card_choices なし' => [
                str_repeat('a', 2000),
                [],
                str_repeat('a', 2000)
            ],
            'card_choices 1個だけ' => [
                str_repeat('a', 2000),
                [
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => true
                    ]
                ],
                str_repeat('a', 2000)
            ],
            'card_choices 正解2個' => [
                str_repeat('a', 2000),
                [
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => true
                    ],
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => true
                    ]
                ],
                str_repeat('a', 2000)
            ],
            'card_choices 正解0個' => [
                str_repeat('a', 2000),
                [
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => false
                    ],
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => false
                    ]
                ],
                str_repeat('a', 2000)
            ],
            'card_choice_text 200文字以上' => [
                str_repeat('a', 2000),
                [
                    [
                        'card_choice_text' => str_repeat('a', 201),
                        'card_choice_is_correct' => true
                    ],
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => false
                    ]
                ],
                str_repeat('a', 2000)
            ],
            'card_explanation 2000文字以上' => [
                str_repeat('a', 2000),
                [
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => true
                    ],
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => false
                    ]
                ],
                str_repeat('a', 2001)
            ],
        ];
    }

    /**
     * 問題の更新ができることをテスト。
     *
     * @return void
     */
    public function testSuccessUpdateCard()
    {
        $updateCard = factory(Card::class)->make();
        $updateCardChoice1 = factory(CardChoice::class)->make([
            'card_choice_is_correct' => true
        ]);
        $updateCardChoice2 = factory(CardChoice::class)->make();
        $questionImage = UploadedFile::fake()->image('question.jpg');
        $explanationImage = UploadedFile::fake()->image('explanation.jpg');

        $this->actingAs($this->user, 'api')
            ->putJson('/books'.'/'.$this->book->book_id.'/cards'.'/'.$this->card->card_id, [
                'card_question' => $updateCard->card_question,
                'card_question_image' => $questionImage,
                'card_choices' => [$updateCardChoice1, $updateCardChoice2],
                'card_explanation' => $updateCard->card_explanation,
                'card_explanation_image' => $explanationImage
            ])->assertStatus(200);

        $this->assertDatabaseHas('cards', [
            'book_id' => $this->book->book_id,
            'card_question' => $updateCard->card_question,
            'card_explanation' => $updateCard->card_explanation
        ]);
        $this->assertDatabaseHas('card_choices', [
            'card_choice_text' => $updateCardChoice1->card_choice_text,
            'card_choice_is_correct' => $updateCardChoice1->card_choice_is_correct
        ]);
        $this->assertDatabaseHas('card_choices', [
            'card_choice_text' => $updateCardChoice2->card_choice_text,
            'card_choice_is_correct' => $updateCardChoice2->card_choice_is_correct
        ]);

        $storedCard = Card::where([
            'book_id' => $this->book->book_id,
            'card_question' => $updateCard->card_question,
            'card_explanation' => $updateCard->card_explanation
        ])->first();

        $this->assertEquals(
            $storedCard->card_order,
            Card::where('book_id', $this->book->book_id)->max('card_order')
        );

        $imageBasePath = 'public/images/cards/'.$storedCard->card_id;
        $imageQuestionPath = $imageBasePath.'/'.$storedCard->card_question_image;
        $imageExplanationPath = $imageBasePath.'/'.$storedCard->card_explanation_image;
        Storage::disk('local')->assertExists($imageQuestionPath);
        Storage::disk('local')->assertExists($imageExplanationPath);
    }

    /**
     * 問題の更新が失敗することをテスト（バリデーションのテスト）。
     *
     * @dataProvider dataproviderFailUpdateCard
     * @param string $question
     * @param array $choices
     * @param string $explanation
     * @return void
     */
    public function testFailUpdateCard($question, $choices, $explanation)
    {
        $this->actingAs($this->user, 'api')
            ->putJson('/books'.'/'.$this->book->book_id.'/cards'.'/'.$this->card->card_id, [
                'card_question' => $question,
                'card_question_image' => null,
                'card_choices' => $choices,
                'card_explanation' => $explanation,
                'card_explanation_image' => null
            ])->assertStatus(422);
    }

    public function dataproviderFailUpdateCard()
    {
        return [
            'card_question なし' => [
                '',
                [
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => true
                    ],
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => false
                    ]
                ],
                str_repeat('a', 2000)
            ],
            'card_question 2000文字以上' => [
                str_repeat('a', 2001),
                [
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => true
                    ],
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => false
                    ]
                ],
                str_repeat('a', 2000)
            ],
            'card_choices なし' => [
                str_repeat('a', 2000),
                [],
                str_repeat('a', 2000)
            ],
            'card_choices 1個だけ' => [
                str_repeat('a', 2000),
                [
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => true
                    ]
                ],
                str_repeat('a', 2000)
            ],
            'card_choices 正解2個' => [
                str_repeat('a', 2000),
                [
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => true
                    ],
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => true
                    ]
                ],
                str_repeat('a', 2000)
            ],
            'card_choices 正解0個' => [
                str_repeat('a', 2000),
                [
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => false
                    ],
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => false
                    ]
                ],
                str_repeat('a', 2000)
            ],
            'card_choice_text 200文字以上' => [
                str_repeat('a', 2000),
                [
                    [
                        'card_choice_text' => str_repeat('a', 201),
                        'card_choice_is_correct' => true
                    ],
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => false
                    ]
                ],
                str_repeat('a', 2000)
            ],
            'card_explanation 2000文字以上' => [
                str_repeat('a', 2000),
                [
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => true
                    ],
                    [
                        'card_choice_text' => str_repeat('a', 200),
                        'card_choice_is_correct' => false
                    ]
                ],
                str_repeat('a', 2001)
            ],
        ];
    }

    /**
     * 問題の順番更新が成功することをテスト。
     *
     * @return void
     */
    public function testSuccessUpdateCardOrder()
    {
        $bookId = $this->book->book_id;
        $card1 = $this->card;
        $card2 = factory(Card::class)->create([
            'book_id' => $this->book->book_id,
            'card_order' => Card::where('book_id', $bookId)
                ->max('card_order') + 1
        ]);
        $card3 = factory(Card::class)->create([
            'book_id' => $this->book->book_id,
            'card_order' => Card::where('book_id', $bookId)
                ->max('card_order') + 1
        ]);

        $cardIds = [$card3->card_id, $card1->card_id, $card2->card_id];
        $this->actingAs($this->user, 'api')
            ->putJson('/books'.'/'.$bookId.'/cards/order', ['card_ids' => $cardIds]);

        $this->assertDatabaseHas('cards', [
            'card_id' => $card1->card_id,
            'card_order' => 2
        ]);
        $this->assertDatabaseHas('cards', [
            'card_id' => $card2->card_id,
            'card_order' => 3
        ]);
        $this->assertDatabaseHas('cards', [
            'card_id' => $card3->card_id,
            'card_order' => 1
        ]);
    }

    /**
     * 問題の削除が成功することをテスト。
     *
     * @return void
     */
    public function testSuccessDeleteCard()
    {
        $bookId = $this->card->book_id;
        $cardId = $this->card->card_id;
        $this->actingAs($this->user, 'api')
            ->deleteJson('/books'.'/'.$bookId.'/cards'.'/'.$cardId);

        $this->assertDatabaseMissing('cards', ['card_id' => $cardId]);
    }
}
