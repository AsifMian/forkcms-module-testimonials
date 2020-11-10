{*
	variables that are available:
	- {$widgetTestiMonialsRecentPosts}
*}
<section id="blogRecentCommentsWidget" class="mod">
	<div class="inner">
		<header class="hd">
			<h3>{$lblRecentArticles|ucfirst}</h3>
		</header>
		<div class="bd content">
			{option:widgetTestiMonialsRecentPosts}
				<ul>
					{iteration:widgetTestiMonialsRecentPosts}
						<li>
							<a href="{$widgetTestiMonialsRecentPosts.full_url}">{$widgetTestiMonialsRecentPosts.title}</a>
							{$msgWrittenBy|ucfirst|sprintf:{$widgetTestiMonialsRecentPosts.user_id|usersetting:'nickname'}} {$lblOn} {$widgetTestiMonialsRecentPosts.edited|date:{$dateFormatShort}:{$LANGUAGE}}
						</li>
					{/iteration:widgetTestiMonialsRecentPosts}
				</ul>
			{/option:widgetTestiMonialsRecentPosts}
			{option:!widgetTestiMonialsRecentPosts}
				{$msgThereAreNoRecentItemsYet}
			{/option:!widgetTestiMonialsRecentPosts}
		</div>
	</div>
</section>
