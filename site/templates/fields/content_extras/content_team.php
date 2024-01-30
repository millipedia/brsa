<?php namespace ProcessWire;

$tick = 0;
echo '<div class="team">';
foreach ($page->team_members as $tp) {

	echo '<div class="team_member">';

		echo '<div class="team_image">';
			echo '<div class="team_image_roundel">';
			echo '<div class="team_image_swoosh"></div>';

				if ($tp->featured_image) {
					echo source_set($tp->featured_image, '', '', 200, 200);
				}else{
					echo '<figure>';
						echo '<picture>';
							echo '<img class="img-fluid card-placeholder" src="/site/assets/images/placeholder_person.svg" alt="" width="200" height="200" loading=lazy>';
						echo '</picture>';
					echo '</figure>';
				}

			echo '</div>';
		echo '</div>';

		echo '<div class="team_meta">';
			echo '<div class="team_member_name">' . $tp->title . '</div>';
			echo '<div class="team_member_strap">' . $tp->strap . '</div>';
			echo '<div class="team_member_separator"></div>';
			echo '<div class="team_member_institution">' . $tp->summary . '</div>';
			echo '<div class="team_member_link_container">';
				echo '<a class="team_member_link" href="' . $tp->bio_link . '">';

					echo '<div class="team_member_link_icon"><svg height="32" viewBox="0 0 32 32" width="32" xmlns="http://www.w3.org/2000/svg"><path d="m16 0a16 16 0 1 1 -16 16 16 16 0 0 1 16-16z" fill="#f24207"/><path d="m14.742 20.448-1.135 1.136a2.256 2.256 0 1 1 -3.191-3.191l3.191-3.19a2.257 2.257 0 0 1 3.19 0 2.233 2.233 0 0 1 .557.956 1.08 1.08 0 0 0 .246-.159l1.483-1.482a4.438 4.438 0 0 0 -.685-.911 4.512 4.512 0 0 0 -6.381 0l-3.196 3.193a4.512 4.512 0 1 0 6.379 6.379l2.418-2.418a5.657 5.657 0 0 1 -2.878-.313zm8.437-5.248-3.191 3.19a4.459 4.459 0 0 1 -7.066-.91l1.478-1.48a1.06 1.06 0 0 1 .241-.16 2.219 2.219 0 0 0 .557.957 2.258 2.258 0 0 0 3.19 0l3.19-3.19a2.256 2.256 0 1 0 -3.19-3.191l-1.135 1.136a5.659 5.659 0 0 0 -2.878-.313l2.425-2.418a4.512 4.512 0 1 1 6.379 6.379z" fill="fill-red"/></svg></div>';
					echo '<div class="team_member_link_text">Link to biography</div>';

				echo '</a>';
			echo '</div>';
		echo '</div>';

	echo '</div>';
}

echo '</div>';
