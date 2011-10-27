<?php
$__elgg_ts = time();

$__elgg_token = generate_action_token($__elgg_ts); 
?>

<script type="text/javascript">
var makeResultRow = function(question) {
	var up_votes = question.up_vote_count,
	    title = question.title,
	    question_id = question.question_id,
	    answers_count = question.answer_count;
	return '<div class="result-row" style="display:table-row;">' +
		'<a href="#" question_id="' + question_id + '">' +
		'<span style="width:10%;padding:10px;margin:10px;">'  + up_votes + ' votes</span>' +
		'<span style="width:80%;">' + title + '(' + answers_count + ')</span>' + 
		'</a>' +
		'</div>';
};

var getQuestionDetails = function(question_id) {
	var url = "<?php echo $vars['url']; ?>action/stackoverflow/details",
	    question_title,	    
	    question_body,
	    answers,
	    answer_title,
	    answer_body,
	    questions,
	    i;

	jQuery.ajax({
		url: url,
		data: {
			"question_id": question_id, 
			"__elgg_token": "<?php echo $__elgg_token; ?>",
			"__elgg_ts": "<?php echo $__elgg_ts; ?>"
		},
		dataType: 'json',
		success: function(data) {
			jQuery('.question-details').children().remove();
			questions = data.questions;
			question_title = '<h1>' + questions[0].title + '</h1>';
			question_body = questions[0].body;
			answers  = questions[0].answers;
			jQuery('.question-details').append( question_title ).
				append('<button class="share" question_id="' + question_id + '>share</a>').
				append(question_body).append('<h2>Answers</h2><hr/>');
			for (i = 0; answers && i < answers.length; i+=1) {
				jQuery('.question-details').append( answers[i].body ).
					append('<br/> <br /><hr/> ');
			}
		},
	});
},
	saveQuestion = function(question_id) {
		var url = "<?php echo $vars['url']; ?>action/stackoverflow/save";
		jQuery.get(url, 
			{"question_id": question_id, 
			"__elgg_ts": "<?php echo $__elgg_ts; ?>", 
			"__elgg_token": "<?php echo $__elgg_token; ?>"},
			function() {
			});
	},
	compareFunction = function(a, b) {
		return b.up_vote_count - a.up_vote_count;
	},
	search = function(title) {
		var url = "<?php echo $vars['url']; ?>action/stackoverflow/search",
			i,
			questions;

		jQuery.ajax({
			url: url,
			data: jQuery('.elgg-form-stackoverflow-search').serialize(),
			dataType: 'json',
			success: function(data) {
				questions = data.questions.sort(compareFunction);
				jQuery('#stackoverflow-search-results > div').
					replaceWith();
				if (questions.length === 0) {
					jQuery('#stackoverflow-search-results').
						append('<div>No matching results found</div>');
					return;
				}
				for(i = 0; i < questions.length; i+=1) {
					jQuery('#stackoverflow-search-results').
						append(makeResultRow(questions[i]));
				}	
			}
		});
		return false;
	};
jQuery(document).ready(function() {
	jQuery('.elgg-form-stackoverflow-search').submit(function(e) {
		e.preventDefault();
		search($(this).val());
		return false;
	});
	jQuery('#stackoverflow-search-results').click(function(e) {
		e.preventDefault();
		var link  = $(e.target).closest('a'),
	 	    question_id = $(link).attr('question_id');
		getQuestionDetails(question_id);
	});
	jQuery('.share').live('click', function(e){
		e.preventDefault();
		var question_id = jQuery(this).attr('question_id'),
			url = "<?php echo $vars['url']; ?>action/stackoverflow/save",
			__elgg_token = "<?php echo $__elgg_token; ?>",
			__elgg_ts = "<?php echo $__elgg_ts; ?>";

		jQuery.post(url, 
				{"question_id" : question_id, 
				"__elgg_ts" : __elgg_ts, 
				"__elgg_token" : __elgg_token} ,
				function() {
					jQuery('.share').html('shared');
			});
	});
	jQuery('#ajax-progress').
		hide().
		ajaxStart(function(){
			jQuery(this).show();
		}).
		ajaxStop(function() {
			jQuery(this).hide();
		});
	jQuery('#stackoverflow-search-results, .question-details').
		ajaxStart(function() {
			jQuery(this).hide();
		}).
		ajaxStop(function(){
			jQuery(this).show();
		});
});
</script>
