/*

!!! CAUTION !!! Somehow does not works based on "PageTSconfig File". For now, keep going with database record based grids.

tx_gridelements.setup {

  # Identifier
  1 {
    title = 2 Columns Grid
    description = Standard bootstrap two column grid
    //icon = EXT:mhtemplatelmw/Resources/Public/Icons/BackendLayouts/accordeon.png

    config {
      colCount = 2
      rowCount = 1
      rows {
        1 {
          columns {
            1 {
              name = Column 1
              colPos = 1
            }
            2 {
              name = Column 2
              colPos = 2
            }
          }
        }
      }
    }
    flexformDS = FILE:EXT:ns_basetheme/Configuration/PageTSconfig/GridElements/2col_flexform.xml
  }
}
*/