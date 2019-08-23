					<div class="box -padding-20" style="margin-bottom:30px;">
						<h3><?php echo Label::getLabel('LBL_Lessons_Packages'); ?></h3>
						<p><?php echo Label::getLabel('LBL_How_many_lessons_would_you_like_to_purchase?'); ?></p>

						<div class="selection-list">
							<ul>
								<?php foreach($lessonPackages as $lpackage){ ?>
								<li class="<?php echo ($cartData['lpackage_id'] == $lpackage['lpackage_id']) ? 'is-active' : ''; ?>">
									<label class="selection">
										<span class="radio">
											<input onClick="addToCart('<?php echo $cartData['user_id'] ?>', '<?php echo $lpackage['lpackage_id']; ?>', '<?php echo $languageId; ?>');" type="radio" <?php echo ($cartData['lpackage_id'] == $lpackage['lpackage_id']) ? 'checked="checked"' : ''; ?> name="lpackage_qty" value="<?php echo $lpackage['lpackage_id']; ?>"><i class="input-helper"></i>
										</span>
										<span class="selection__item">
											<?php echo $lpackage['lpackage_title']." (".$lpackage['lpackage_lessons']." Lessons)"; ?> <small class="-float-right"> <?php echo CommonHelper::displayMoneyFormat( ($lpackage['lpackage_lessons']>1)?$selectedLang['utl_bulk_lesson_amount']:$selectedLang['utl_single_lesson_amount'] ); ?>/ <?php echo Label::getLabel('LBL_Per_Hour'); ?></small>
										</span>
									</label>
								</li>
							<?php } ?>
							</ul>
						</div>

					</div>