<section class="question">
    <div class="container">
        <div class="question-form">
            <div class="row">
                <div class="col-md-12 col-xl-5">
                    <div class="big-h1">Остались вопросы?</div>
                    <b>Перезвоним за 30 секунд</b>
                </div>
                <form @submit.prevent="submitQuestionForm" class="question-form__inputs col-md-12 col-xl-7 d-flex flex-wrap flex-md-nowrap justify-content-xl-end align-items-center">
                    <input type="text" placeholder="Ваше имя" v-model="question.name">
                    <input type="text" placeholder="Ваш телефон" v-model="question.phone" v-mask="'+7 (###) ###-##-##'">
                    <button type="submit">Позвоните мне</button>
                </form>
            </div>
        </div>
    </div>
</section>