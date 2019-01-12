
tt_content.gridelements_pi1.20.10 {
	setup {
		# Two Column Images with text
		1 {
			columns {
				1 {
					renderObj =< tt_content
					wrap =  <div class="col-sm-6">|</div>
				}
				2 {
					renderObj =< tt_content
					wrap =  <div class="col-sm-6"><div id="latest-news">|</div></div>
				}						
			}
			wrap = <div class="row" id="row1">|</div>
		}	
	}
}