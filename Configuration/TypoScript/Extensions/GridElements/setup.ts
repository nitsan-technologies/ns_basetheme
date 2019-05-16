# TypoScript for rendering in frontend
tt_content.gridelements_pi1.20.10.setup {

    # Two column grid container
    1 < lib.gridelements.defaultGridSetup
    1 {
        columns {
            1 < .default
            1 {
                preCObject = CASE
                preCObject {
                    key.field = flexform_columnType
                    cols-1-2 = TEXT
                    cols-1-2 {
                        value = <div class="col-md-4">
                    }

                    cols-2-1 = TEXT
                    cols-2-1 {
                        value = <div class="col-md-8">
                    }

                    cols-1-3 = TEXT
                    cols-1-3 {
                        value = <div class="col-md-3">
                    }

                    cols-3-1 = TEXT
                    cols-3-1 {
                        value = <div class="col-md-9">
                    }

                    default = TEXT
                    default {
                        value = <div class="col-md-6">
                    }
                }

                wrap = |</div>
            }

            2 < .default
            2 {
                preCObject = CASE
                preCObject {
                    key.field = flexform_columnType
                    cols-1-2 = TEXT
                    cols-1-2 {
                        value = <div class="col-md-8">
                    }

                    cols-2-1 = TEXT
                    cols-2-1 {
                        value = <div class="col-md-4">
                    }

                    cols-1-3 = TEXT
                    cols-1-3 {
                        value = <div class="col-md-9">
                    }

                    cols-3-1 = TEXT
                    cols-3-1 {
                        value = <div class="col-md-3">
                    }

                    default = TEXT
                    default {
                        value = <div class="col-md-6">
                    }
                }

                wrap = |</div>
            }
        }

        stdWrap.wrap.cObject = COA
        stdWrap.wrap.cObject {
            10 = TEXT
            10.value = <div class="row">|</div>
        }
    }

    # Three column grid container
    2 < lib.gridelements.defaultGridSetup
    2 {
        columns {
            1 < .default
            1 {
                preCObject = CASE
                preCObject {
                    key.field = flexform_columnType
                    cols-1-1-1 = TEXT
                    cols-1-1-1 {
                        value = <div class="col-md-4">
                    }

                    default = TEXT
                    default {
                        value = <div class="col-md-4">
                    }
                }

                wrap = |</div>
            }

            2 < .default
            2 {
                preCObject = CASE
                preCObject {
                    key.field = flexform_columnType
                    cols-1-1-1 = TEXT
                    cols-1-1-1 {
                        value = <div class="col-md-4">
                    }

                    default = TEXT
                    default {
                        value = <div class="col-md-4">
                    }
                }

                wrap = |</div>
            }

            3 < .default
            3 {
                preCObject = CASE
                preCObject {
                    key.field = flexform_columnType
                    cols-1-1-1 = TEXT
                    cols-1-1-1 {
                        value = <div class="col-md-4">
                    }

                    default = TEXT
                    default {
                        value = <div class="col-md-4">
                    }
                }

                wrap = |</div>
            }
        }

        stdWrap.wrap.cObject = COA
        stdWrap.wrap.cObject {
            10 = TEXT
            10.value = <div class="row">|</div>
        }
    }

    # Four column grid container
    3 < lib.gridelements.defaultGridSetup
    3 {
        columns {
            1 < .default
            1 {
                preCObject = CASE
                preCObject {
                    key.field = flexform_columnType
                    cols-1-1-1 = TEXT
                    cols-1-1-1 {
                        value = <div class="col-md-3">
                    }

                    default = TEXT
                    default {
                        value = <div class="col-md-3">
                    }
                }

                wrap = |</div>
            }

            2 < .default
            2 {
                preCObject = CASE
                preCObject {
                    key.field = flexform_columnType
                    cols-1-1-1 = TEXT
                    cols-1-1-1 {
                        value = <div class="col-md-3">
                    }

                    default = TEXT
                    default {
                        value = <div class="col-md-3">
                    }
                }

                wrap = |</div>
            }

            3 < .default
            3 {
                preCObject = CASE
                preCObject {
                    key.field = flexform_columnType
                    cols-1-1-1 = TEXT
                    cols-1-1-1 {
                        value = <div class="col-md-3">
                    }

                    default = TEXT
                    default {
                        value = <div class="col-md-3">
                    }
                }

                wrap = |</div>
            }

            4 < .default
            4 {
                preCObject = CASE
                preCObject {
                    key.field = flexform_columnType
                    cols-1-1-1 = TEXT
                    cols-1-1-1 {
                        value = <div class="col-md-3">
                    }

                    default = TEXT
                    default {
                        value = <div class="col-md-3">
                    }
                }

                wrap = |</div>
            }
        }

        stdWrap.wrap.cObject = COA
        stdWrap.wrap.cObject {
            10 = TEXT
            10.value = <div class="row">|</div>
        }
    }
}