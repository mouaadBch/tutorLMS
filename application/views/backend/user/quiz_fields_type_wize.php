<?php if($question_type == 'multiple_choice' || $question_type == 'single_choice'): ?>
    <div class="form-group">
        <label for="number_of_options"><?php echo get_phrase('number_of_options'); ?></label>
        <div class="input-group">
            <input type="number" value="<?php if(isset($question_details)) echo $question_details['number_of_options']; ?>" onkeyup="appendOptions(this.value, '<?php echo $question_type; ?>')" class="form-control" name="number_of_options" id="number_of_options" data-validate="required" data-message-required="Value Required" min="0">
        </div>
    </div>

    <div id="mcq_choice_input_options">
        <?php if(isset($question_details)): ?>
            <?php foreach(json_decode($question_details['options']) as $key => $option): ?>
                <?php $option_type = ($question_type == 'multiple_choice') ? 'checkbox':'radio'; ?>
                <?php $key++; ?>
                <div class="form-group options">
                    <label><?php echo get_phrase("option"); ?> <?php echo $key; ?></label>
                    <div class="input-group">
                        <textarea class="form-control optionMouaad" name = "options[]" id="option_<?php echo $key; ?>"  required><?php echo $option; ?></textarea>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <input type="<?php echo $option_type; ?>" name = "correct_answers[]" value = "<?php echo $key; ?>" <?php if(in_array($key, json_decode($question_details['correct_answers']))) echo 'checked'; ?>>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script type="text/javascript">
        function appendOptions(val, question_type){
            $('#mcq_choice_input_options').html('');

            if(question_type == 'multiple_choice'){
                var optionType = "checkbox";
            }else{
                var optionType = "radio";
            }
            for(var i=1; i <= val; i++){
                var field = '<div class="form-group options"><label><?php echo get_phrase("option"); ?> '+i+'</label><div class="input-group"><textarea class="form-control" name = "options[]" id="option_'+i+'" required></textarea><div class="input-group-append"><span class="input-group-text"><input type="'+optionType+'" name = "correct_answers[]" value = '+i+'></span></div></div></div>';

                $('#mcq_choice_input_options').append(field);
                initSummerNote(['#option_' + i]);
            }
        }
    </script>
<?php elseif($question_type == 'fill_in_the_blank'): ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.3.0/tagify.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.3.0/tagify.min.js"></script>
    <div class="form-group">
        <label for="correct_answers"><?php echo get_phrase('enter_which_word_of_your_question_you_want to_show_blank'); ?><span class="text-muted">(_______)</span>?</label>
        <input type="text"  id = "correct_answers" name="correct_answers" style="width: 100%;" class="form-control bootstrap-tag-input" value="<?php if(isset($question_details)){foreach(json_decode($question_details['correct_answers']) as $answer){echo $answer.',';}} ?>" />
        <small><?php echo get_phrase('press_the_enter_key_after_writing_your_every_word'); ?>.</small>
    </div>
    <script type="text/javascript">
        $(function(){
            new Tagify(document.querySelector("#correct_answers"), {
                duplicates: true,
            });
        });
    </script>
<?php endif; ?>
