<div class="mbt-metabox-tabs-wrapper mbt-layout-<?php echo $layout; ?> wp-clearfix" data-mbt-id="<?php echo $metabox_id; ?>">
    <div class="<?php echo ( 'vertical' == $layout ) ? 'mbt-row' : 'mbt-col-left'; ?>">
        <div class="mbt-metabox-tabs-wrap">
            <ul class="mbt-metabox-tabs" data-container=".mbt-metabox-tabs-content">
                <?php $i = 0; foreach ( $tabs as $id => $tab ) : ?>
                    <li class="mbt-metabox-tab<?php echo ( 0 == $i ) ? ' active' : ''; ?>" data-tab="<?php echo $id; ?>">
                        <a href="#mbt-metabox-tab-<?php echo $id; ?>" data-tab="<?php echo $id; ?>">
                            <?php if ( isset( $tab['icon'] ) && ! empty( $tab['icon'] ) ) : ?>
                                <span class="<?php echo $tab['icon']; ?>"></span>
                            <?php endif; ?>
                            <span class="mbt-metabox-tab-title"><?php echo ( isset( $tab['title'] ) ) ? $tab['title'] : ''; ?></span>
                        </a>
                    </li>
                <?php $i++; endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="<?php echo ( 'vertical' == $layout ) ? 'mbt-row' : 'mbt-col-right'; ?>">
        <div class="mbt-metabox-tabs-content">
            <?php $i = 0; foreach ( $tabs as $id => $tab ) : // Tabs ?>
                <div id="mbt-metabox-tab-<?php echo $id; ?>" class="mbt-metabox-tab-content<?php echo ( 0 == $i ) ? ' active' : ''; ?>">

                    <?php if ( isset( $tab['description'] ) && ! empty( $tab['description'] ) ) : ?>
                        <p class="mbt-metabox-tab-description"><?php echo $tab['description']; ?></p>
                    <?php endif; ?>

                    <?php foreach ( $tab['sections'] as $id => $section ) : // Sections ?>
                        <div id="mbt-metabox-section-<?php echo $id; ?>" class="mbt-metabox-section">
                            <?php if ( isset( $section['title'] ) && ! empty( $section['title'] ) ) : ?>
                                <h2 class="mbt-metabox-section-title"><?php echo $section['title']; ?></h2>
                            <?php endif; ?>

                            <?php if ( isset( $section['description'] ) && ! empty( $section['description'] ) ) : ?>
                                <p class="mbt-metabox-section-description"><?php echo $section['description']; ?></p>
                            <?php endif; ?>

                            <div class="mbt-metabox-section-content">
                                <table class="mbt-metabox-form-table form-table">
                                    <?php
                                        foreach ( $section['fields'] as $name => $field ) : // Fields
                                            MetaBox_Tabs::render_metabox_field( $name, $field );
                                        endforeach;
                                    ?>
                                </table>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php $i++; endforeach; ?>
        </div>
    </div>
</div>
