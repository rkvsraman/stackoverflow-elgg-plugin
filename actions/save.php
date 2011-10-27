<?php
  // only logged in users can add blog posts
  // gatekeeper

$question_id = get_input('question_id');
$endpoint = 'http://api.stackoverflow.com/1.1/questions/' . $question_id . '?type=jsontext&body=true&answers=true&comments=true';
$response = json_decode(http_inflate(file_get_contents($endpoint)));
$questions = $response->questions[0];


$question_title = $questions->title;
$question_body = add_link_to_original( $questions->body, $question_id );
$answers = $questions->answers;
$elgg_question = save_question($question_title, $question_body);

foreach ($answers as $answer) {
	$answer_body = $answer->body;
	$elgg_answer = save_answer($elgg_question, $answer_body);

	$comments = $answer->comments;
	foreach ($comments as $comment) {
		$comment = $comment->body;
		save_comment($elgg_answer, $comment);
	}
}

echo json_encode(array('success' => true));
die();

function add_link_to_original($body, $question_id) {
	$url = 'http://www.stackoverflow.com/questions/' . $question_id;

	$message = "<div style='border:2px solid green;'>See the original question at <a href='$url'>Stackoverflow</a></div>";
	return "$message$body";
}
function save_answer($elgg_question, $answer_body) {
	$answer = new ElggObject();
	$answer->subtype = "answer";
	$answer->description = $answer_body;
	$answer->question_guid = $elgg_question->getGUID();

	$answer->save();
	add_entity_relationship(
		$elgg_question->getGUID(), 
		"answer", 
		$answer->getGUID()
	);
	return $answer;
}

function save_comment($elgg_answer, $comment_text) {
	$elgg_answer->annotate("comment", $comment_text, $elgg_answer->access_id, $_SESSION['guid']);
}

function save_question($title, $body) {
	$question = new ElggObject();
	$question->subtype = "question";
	$question->access_id = ACCESS_PUBLIC;
	$question->title = $title;
	$question->description = $body;
	$question->save();
	return $question;
}


