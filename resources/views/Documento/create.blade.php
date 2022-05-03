@extends('adminlte::page')




@section('content')

  <div id="allSampleContent" class="p-4 w-full">
               
               
                   
 
       
@endsection

@section('css')

{{-- <link type=‘text/css’ rel=‘stylesheet’ href="{{asset('/css/UDStyle.css') }}" />
@stop --}}

@section('js')
<script src="https://unpkg.com/gojs@2.2.7/release/go.js" ></script>

<script id="code">
  function init() {

    // Since 2.2 you can also author concise templates with method chaining instead of GraphObject.make
    // For details, see https://gojs.net/latest/intro/buildingObjects.html
    const $ = go.GraphObject.make;

    myDiagram =
      $(go.Diagram, "myDiagramDiv",
        {
          padding: 20,  // extra space when scrolled all the way
          grid: $(go.Panel, "Grid",  // a simple 10x10 grid
            $(go.Shape, "LineH", { stroke: "lightgray", strokeWidth: 0.5 }),
            $(go.Shape, "LineV", { stroke: "lightgray", strokeWidth: 0.5 })
          ),
          "draggingTool.isGridSnapEnabled": true,
          handlesDragDropForTopLevelParts: true,
          mouseDrop: e => {
            // when the selection is dropped in the diagram's background,
            // make sure the selected Parts no longer belong to any Group
            var ok = e.diagram.commandHandler.addTopLevelParts(e.diagram.selection, true);
            if (!ok) e.diagram.currentTool.doCancel();
          },
          commandHandler: $(DrawCommandHandler),  // support offset copy-and-paste
          "clickCreatingTool.archetypeNodeData": { text: "NEW NODE" },  // create a new node by double-clicking in background
          "PartCreated": e => {
            var node = e.subject;  // the newly inserted Node -- now need to snap its location to the grid
            node.location = node.location.copy().snapToGridPoint(e.diagram.grid.gridOrigin, e.diagram.grid.gridCellSize);
            setTimeout(() => {  // and have the user start editing its text
              e.diagram.commandHandler.editTextBlock();
            }, 20);
          },
          "commandHandler.archetypeGroupData": { isGroup: true, text: "NEW GROUP" },
          "SelectionGrouped": e => {
            var group = e.subject;
            setTimeout(() => {  // and have the user start editing its text
              e.diagram.commandHandler.editTextBlock();
            })
          },
          "LinkRelinked": e => {
            // re-spread the connections of other links connected with both old and new nodes
            var oldnode = e.parameter.part;
            oldnode.invalidateConnectedLinks();
            var link = e.subject;
            if (e.diagram.toolManager.linkingTool.isForwards) {
              link.toNode.invalidateConnectedLinks();
            } else {
              link.fromNode.invalidateConnectedLinks();
            }
          },
          "undoManager.isEnabled": true
        });


    // Node template

    myDiagram.nodeTemplate =
      $(go.Node, "Auto",
        {
          locationSpot: go.Spot.Center, locationObjectName: "SHAPE",
          desiredSize: new go.Size(120, 60), minSize: new go.Size(40, 40),
          resizable: true, resizeCellSize: new go.Size(20, 20)
        },
        // these Bindings are TwoWay because the DraggingTool and ResizingTool modify the target properties
        new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
        new go.Binding("desiredSize", "size", go.Size.parse).makeTwoWay(go.Size.stringify),
        $(go.Shape,
          { // the border
            name: "SHAPE", fill: "white",
            portId: "", cursor: "pointer",
            fromLinkable: true, toLinkable: true,
            fromLinkableDuplicates: true, toLinkableDuplicates: true,
            fromSpot: go.Spot.AllSides, toSpot: go.Spot.AllSides
          },
          new go.Binding("figure"),
          new go.Binding("fill"),
          new go.Binding("stroke", "color"),
          new go.Binding("strokeWidth", "thickness"),
          new go.Binding("strokeDashArray", "dash")),
        // this Shape prevents mouse events from reaching the middle of the port
        $(go.Shape, { width: 100, height: 40, strokeWidth: 0, fill: "transparent" }),
        $(go.TextBlock,
          { margin: 1, textAlign: "center", overflow: go.TextBlock.OverflowEllipsis, editable: true },
          // this Binding is TwoWay due to the user editing the text with the TextEditingTool
          new go.Binding("text").makeTwoWay(),
          new go.Binding("stroke", "color"))
      );

    myDiagram.nodeTemplate.toolTip =
      $("ToolTip",  // show some detailed information
        $(go.Panel, "Vertical",
          { maxSize: new go.Size(200, NaN) },  // limit width but not height
          $(go.TextBlock,
            { font: "bold 10pt sans-serif", textAlign: "center" },
            new go.Binding("text")),
          $(go.TextBlock,
            { font: "10pt sans-serif", textAlign: "center" },
            new go.Binding("text", "details"))
        )
      );

    // Node selection adornment
    // Include four large triangular buttons so that the user can easily make a copy
    // of the node, move it to be in that direction relative to the original node,
    // and add a link to the new node.

    function makeArrowButton(spot, fig) {
      var maker = (e, shape) => {
          e.handled = true;
          e.diagram.model.commit(m => {
            var selnode = shape.part.adornedPart;
            // create a new node in the direction of the spot
            var p = new go.Point().setRectSpot(selnode.actualBounds, spot);
            p.subtract(selnode.location);
            p.scale(2, 2);
            p.x += Math.sign(p.x) * 60;
            p.y += Math.sign(p.y) * 60;
            p.add(selnode.location);
            p.snapToGridPoint(e.diagram.grid.gridOrigin, e.diagram.grid.gridCellSize);
            // make the new node a copy of the selected node
            var nodedata = m.copyNodeData(selnode.data);
            // add to same group as selected node
            m.setGroupKeyForNodeData(nodedata, m.getGroupKeyForNodeData(selnode.data));
            m.addNodeData(nodedata);  // add to model
            // create a link from the selected node to the new node
            var linkdata = { from: selnode.key, to: m.getKeyForNodeData(nodedata) };
            m.addLinkData(linkdata);  // add to model
            // move the new node to the computed location, select it, and start to edit it
            var newnode = e.diagram.findNodeForData(nodedata);
            newnode.location = p;
            e.diagram.select(newnode);
            setTimeout(() => {
              e.diagram.commandHandler.editTextBlock();
            }, 20);
          });
        };
      return $(go.Shape,
        {
          figure: fig,
          alignment: spot, alignmentFocus: spot.opposite(),
          width: (spot.equals(go.Spot.Top) || spot.equals(go.Spot.Bottom)) ? 36 : 18,
          height: (spot.equals(go.Spot.Top) || spot.equals(go.Spot.Bottom)) ? 18 : 36,
          fill: "orange", strokeWidth: 0,
          isActionable: true,  // needed because it's in an Adornment
          click: maker, contextClick: maker
        });
    }

    // create a button that brings up the context menu
    function CMButton(options) {
      return $(go.Shape,
        {
          fill: "orange", stroke: "gray", background: "transparent",
          geometryString: "F1 M0 0 M0 4h4v4h-4z M6 4h4v4h-4z M12 4h4v4h-4z M0 12",
          isActionable: true, cursor: "context-menu",
          click: (e, shape) => {
            e.diagram.commandHandler.showContextMenu(shape.part.adornedPart);
          }
        },
        options || {});
    }

    myDiagram.nodeTemplate.selectionAdornmentTemplate =
      $(go.Adornment, "Spot",
        $(go.Placeholder, { padding: 10 }),
        makeArrowButton(go.Spot.Top, "TriangleUp"),
        makeArrowButton(go.Spot.Left, "TriangleLeft"),
        makeArrowButton(go.Spot.Right, "TriangleRight"),
        makeArrowButton(go.Spot.Bottom, "TriangleDown"),
        CMButton({ alignment: new go.Spot(0.75, 0) })
      );

    // Common context menu button definitions

    // All buttons in context menu work on both click and contextClick,
    // in case the user context-clicks on the button.
    // All buttons modify the node data, not the Node, so the Bindings need not be TwoWay.

    // A button-defining helper function that returns a click event handler.
    // PROPNAME is the name of the data property that should be set to the given VALUE.
    function ClickFunction(propname, value) {
      return (e, obj) => {
          e.handled = true;  // don't let the click bubble up
          e.diagram.model.commit(m => {
            m.set(obj.part.adornedPart.data, propname, value);
          });
        };
    }

    // Create a context menu button for setting a data property with a color value.
    function ColorButton(color, propname) {
      if (!propname) propname = "color";
      return $(go.Shape,
        {
          width: 16, height: 16, stroke: "lightgray", fill: color,
          margin: 1, background: "transparent",
          mouseEnter: (e, shape) => shape.stroke = "dodgerblue",
          mouseLeave: (e, shape) => shape.stroke = "lightgray",
          click: ClickFunction(propname, color), contextClick: ClickFunction(propname, color)
        });
    }

    function LightFillButtons() {  // used by multiple context menus
      return [
        $("ContextMenuButton",
          $(go.Panel, "Horizontal",
            ColorButton("white", "fill"), ColorButton("beige", "fill"), ColorButton("aliceblue", "fill"), ColorButton("lightyellow", "fill")
          )
        ),
        $("ContextMenuButton",
          $(go.Panel, "Horizontal",
            ColorButton("lightgray", "fill"), ColorButton("lightgreen", "fill"), ColorButton("lightblue", "fill"), ColorButton("pink", "fill")
          )
        )
      ];
    }

    function DarkColorButtons() {  // used by multiple context menus
      return [
        $("ContextMenuButton",
          $(go.Panel, "Horizontal",
            ColorButton("black"), ColorButton("green"), ColorButton("blue"), ColorButton("red")
          )
        ),
        $("ContextMenuButton",
          $(go.Panel, "Horizontal",
            ColorButton("brown"), ColorButton("magenta"), ColorButton("purple"), ColorButton("orange")
          )
        )
      ];
    }

    // Create a context menu button for setting a data property with a stroke width value.
    function ThicknessButton(sw, propname) {
      if (!propname) propname = "thickness";
      return $(go.Shape, "LineH",
        {
          width: 16, height: 16, strokeWidth: sw,
          margin: 1, background: "transparent",
          mouseEnter: (e, shape) => shape.background = "dodgerblue",
          mouseLeave: (e, shape) => shape.background = "transparent",
          click: ClickFunction(propname, sw), contextClick: ClickFunction(propname, sw)
        });
    }

    // Create a context menu button for setting a data property with a stroke dash Array value.
    function DashButton(dash, propname) {
      if (!propname) propname = "dash";
      return $(go.Shape, "LineH",
        {
          width: 24, height: 16, strokeWidth: 2,
          strokeDashArray: dash,
          margin: 1, background: "transparent",
          mouseEnter: (e, shape) => shape.background = "dodgerblue",
          mouseLeave: (e, shape) => shape.background = "transparent",
          click: ClickFunction(propname, dash), contextClick: ClickFunction(propname, dash)
        });
    }

    function StrokeOptionsButtons() {  // used by multiple context menus
      return [
        $("ContextMenuButton",
          $(go.Panel, "Horizontal",
            ThicknessButton(1), ThicknessButton(2), ThicknessButton(3), ThicknessButton(4)
          )
        ),
        $("ContextMenuButton",
          $(go.Panel, "Horizontal",
            DashButton(null), DashButton([2, 4]), DashButton([4, 4])
          )
        )
      ];
    }

    // Node context menu

    function FigureButton(fig, propname) {
      if (!propname) propname = "figure";
      return $(go.Shape,
        {
          width: 32, height: 32, scale: 0.5, fill: "lightgray", figure: fig,
          margin: 1, background: "transparent",
          mouseEnter: (e, shape) => shape.fill = "dodgerblue",
          mouseLeave: (e, shape) => shape.fill = "lightgray",
          click: ClickFunction(propname, fig), contextClick: ClickFunction(propname, fig)
        });
    }

    myDiagram.nodeTemplate.contextMenu =
      $("ContextMenu",
        $("ContextMenuButton",
          $(go.Panel, "Horizontal",
            FigureButton("Rectangle"), FigureButton("RoundedRectangle"), FigureButton("Ellipse"), FigureButton("Diamond")
          )
        ),
        $("ContextMenuButton",
          $(go.Panel, "Horizontal",
            FigureButton("Parallelogram2"), FigureButton("ManualOperation"), FigureButton("Procedure"), FigureButton("Cylinder1")
          )
        ),
        $("ContextMenuButton",
          $(go.Panel, "Horizontal",
            FigureButton("Terminator"), FigureButton("CreateRequest"), FigureButton("Document"), FigureButton("TriangleDown")
          )
        ),
        LightFillButtons(),
        DarkColorButtons(),
        StrokeOptionsButtons()
      );


    // Group template

    myDiagram.groupTemplate =
      $(go.Group, "Spot",
        {
          layerName: "Background",
          ungroupable: true,
          locationSpot: go.Spot.Center,
          selectionObjectName: "BODY",
          computesBoundsAfterDrag: true,  // allow dragging out of a Group that uses a Placeholder
          handlesDragDropForMembers: true,  // don't need to define handlers on Nodes and Links
          mouseDrop: (e, grp) => {  // add dropped nodes as members of the group
            var ok = grp.addMembers(grp.diagram.selection, true);
            if (!ok) grp.diagram.currentTool.doCancel();
          },
          avoidable: false
        },
        new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
        $(go.Panel, "Auto",
          { name: "BODY" },
          $(go.Shape,
            {
              parameter1: 10,
              fill: "white", strokeWidth: 2,
              portId: "", cursor: "pointer",
              fromLinkable: true, toLinkable: true,
              fromLinkableDuplicates: true, toLinkableDuplicates: true,
              fromSpot: go.Spot.AllSides, toSpot: go.Spot.AllSides
            },
            new go.Binding("fill"),
            new go.Binding("stroke", "color"),
            new go.Binding("strokeWidth", "thickness"),
            new go.Binding("strokeDashArray", "dash")),
          $(go.Placeholder,
            { background: "transparent", margin: 10 })
        ),
        $(go.TextBlock,
          {
            alignment: go.Spot.Top, alignmentFocus: go.Spot.Bottom,
            font: "bold 12pt sans-serif", editable: true
          },
          new go.Binding("text"),
          new go.Binding("stroke", "color"))
      );

    myDiagram.groupTemplate.selectionAdornmentTemplate =
      $(go.Adornment, "Spot",
        $(go.Panel, "Auto",
          $(go.Shape, { fill: null, stroke: "dodgerblue", strokeWidth: 3 }),
          $(go.Placeholder, { margin: 1.5 })
        ),
        CMButton({ alignment: go.Spot.TopRight, alignmentFocus: go.Spot.BottomRight })
      );

    myDiagram.groupTemplate.contextMenu =
      $("ContextMenu",
        LightFillButtons(),
        DarkColorButtons(),
        StrokeOptionsButtons()
      );


    // Link template

    myDiagram.linkTemplate =
      $(go.Link,
        {
          layerName: "Foreground",
          routing: go.Link.AvoidsNodes, corner: 10,
          toShortLength: 4,  // assume arrowhead at "to" end, need to avoid bad appearance when path is thick
          relinkableFrom: true, relinkableTo: true,
          reshapable: true, resegmentable: true
        },
        new go.Binding("fromSpot", "fromSpot", go.Spot.parse),
        new go.Binding("toSpot", "toSpot", go.Spot.parse),
        new go.Binding("fromShortLength", "dir", dir => dir === 2 ? 4 : 0),
        new go.Binding("toShortLength", "dir", dir => dir >= 1 ? 4 : 0),
        new go.Binding("points").makeTwoWay(),  // TwoWay due to user reshaping with LinkReshapingTool
        $(go.Shape, { strokeWidth: 2 },
          new go.Binding("stroke", "color"),
          new go.Binding("strokeWidth", "thickness"),
          new go.Binding("strokeDashArray", "dash")),
        $(go.Shape, { fromArrow: "Backward", strokeWidth: 0, scale: 4/3, visible: false },
          new go.Binding("visible", "dir", dir => dir === 2),
          new go.Binding("fill", "color"),
          new go.Binding("scale", "thickness", t => (2+t)/3)),
        $(go.Shape, { toArrow: "Standard", strokeWidth: 0, scale: 4/3 },
          new go.Binding("visible", "dir", dir => dir >= 1),
          new go.Binding("fill", "color"),
          new go.Binding("scale", "thickness", t => (2+t)/3)),
        $(go.TextBlock,
          { alignmentFocus: new go.Spot(0, 1, -4, 0), editable: true },
          new go.Binding("text").makeTwoWay(),  // TwoWay due to user editing with TextEditingTool
          new go.Binding("stroke", "color"))
      );

    myDiagram.linkTemplate.selectionAdornmentTemplate =
      $(go.Adornment,  // use a special selection Adornment that does not obscure the link path itself
        $(go.Shape,
          { // this uses a pathPattern with a gap in it, in order to avoid drawing on top of the link path Shape
            isPanelMain: true,
            stroke: "transparent", strokeWidth: 6,
            pathPattern: makeAdornmentPathPattern(2)  // == thickness or strokeWidth
          },
          new go.Binding("pathPattern", "thickness", makeAdornmentPathPattern)),
        CMButton({ alignmentFocus: new go.Spot(0, 0, -6, -4) })
      );

    function makeAdornmentPathPattern(w) {
      return $(go.Shape,
        {
          stroke: "dodgerblue", strokeWidth: 2, strokeCap: "square",
          geometryString: "M0 0 M4 2 H3 M4 " + (w+4).toString() + " H3"
        });
    }

    // Link context menu
    // All buttons in context menu work on both click and contextClick,
    // in case the user context-clicks on the button.
    // All buttons modify the link data, not the Link, so the Bindings need not be TwoWay.

    function ArrowButton(num) {
      var geo = "M0 0 M16 16 M0 8 L16 8  M12 11 L16 8 L12 5";
      if (num === 0) {
        geo = "M0 0 M16 16 M0 8 L16 8";
      } else if (num === 2) {
        geo = "M0 0 M16 16 M0 8 L16 8  M12 11 L16 8 L12 5  M4 11 L0 8 L4 5";
      }
      return $(go.Shape,
        {
          geometryString: geo,
          margin: 2, background: "transparent",
          mouseEnter: (e, shape) => shape.background = "dodgerblue",
          mouseLeave: (e, shape) => shape.background = "transparent",
          click: ClickFunction("dir", num), contextClick: ClickFunction("dir", num)
        });
    }

    function AllSidesButton(to) {
      var setter = (e, shape) => {
          e.handled = true;
          e.diagram.model.commit(m => {
            var link = shape.part.adornedPart;
            m.set(link.data, (to ? "toSpot" : "fromSpot"), go.Spot.stringify(go.Spot.AllSides));
            // re-spread the connections of other links connected with the node
            (to ? link.toNode : link.fromNode).invalidateConnectedLinks();
          });
        };
      return $(go.Shape,
        {
          width: 12, height: 12, fill: "transparent",
          mouseEnter: (e, shape) => shape.background = "dodgerblue",
          mouseLeave: (e, shape) => shape.background = "transparent",
          click: setter, contextClick: setter
        });
    }

    function SpotButton(spot, to) {
      var ang = 0;
      var side = go.Spot.RightSide;
      if (spot.equals(go.Spot.Top)) { ang = 270; side = go.Spot.TopSide; }
      else if (spot.equals(go.Spot.Left)) { ang = 180; side = go.Spot.LeftSide; }
      else if (spot.equals(go.Spot.Bottom)) { ang = 90; side = go.Spot.BottomSide; }
      if (!to) ang -= 180;
      var setter = (e, shape) => {
          e.handled = true;
          e.diagram.model.commit(m => {
            var link = shape.part.adornedPart;
            m.set(link.data, (to ? "toSpot" : "fromSpot"), go.Spot.stringify(side));
            // re-spread the connections of other links connected with the node
            (to ? link.toNode : link.fromNode).invalidateConnectedLinks();
          });
        };
      return $(go.Shape,
        {
          alignment: spot, alignmentFocus: spot.opposite(),
          geometryString: "M0 0 M12 12 M12 6 L1 6 L4 4 M1 6 L4 8",
          angle: ang,
          background: "transparent",
          mouseEnter: (e, shape) => shape.background = "dodgerblue",
          mouseLeave: (e, shape) => shape.background = "transparent",
          click: setter, contextClick: setter
        });
    }

    myDiagram.linkTemplate.contextMenu =
      $("ContextMenu",
        DarkColorButtons(),
        StrokeOptionsButtons(),
        $("ContextMenuButton",
          $(go.Panel, "Horizontal",
            ArrowButton(0), ArrowButton(1), ArrowButton(2)
          )
        ),
        $("ContextMenuButton",
          $(go.Panel, "Horizontal",
            $(go.Panel, "Spot",
              AllSidesButton(false),
              SpotButton(go.Spot.Top, false), SpotButton(go.Spot.Left, false), SpotButton(go.Spot.Right, false), SpotButton(go.Spot.Bottom, false)
            ),
            $(go.Panel, "Spot",
              { margin: new go.Margin(0, 0, 0, 2) },
              AllSidesButton(true),
              SpotButton(go.Spot.Top, true), SpotButton(go.Spot.Left, true), SpotButton(go.Spot.Right, true), SpotButton(go.Spot.Bottom, true)
            )
          )
        )
      );

    load();
  }

  // Show the diagram's model in JSON format
  function save() {
    document.getElementById("mySavedModel").value = myDiagram.model.toJson();
    myDiagram.isModified = false;
  }
  function load() {
    myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
  }
  window.addEventListener('DOMContentLoaded', init);
</script>

@stop