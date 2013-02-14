# Tools

Each workspace and desktop is composed of tools. A plugin can define new tools.
Each time the CoreBundle is opening a tool, it'll fire the event
open_tool_workspace|desktop_*claroline_mytool*

## Tools implemetation

### Tool definition

Your plugin must define its properties and the list of its tools in the *Resources/config/config.yml file*.

    plugin:
        # Tools declared by your plugin.
        tools:
          - name: claroline_mytool
            **Currently using classes (prototype). Implementation of css classes not done yet**
            #class: res_text.png
            is_displayable_in_workspace: true
            is_displayable_in_desktop: true

In order to catch the event, your plugin must define a listener in your config.

This example will show you the main files of a basic HTML5 video player.

### Tool listener definition

**The listener config file**

*Claroline\VideoPlayer\Resources\config\services\listener.yml*

  claroline.listener.example_tool:
    class: Claroline\ExampleBundle\Listener\ToolListener
    calls:
      - [setContainer, ["@service_container"]]
    tags:
      - { name: kernel.event_listener, event: open_tool_workspace_claroline_mytool, method: onWorkspaceOpen }
      - { name: kernel.event_listener, event: open_tool_desktop_claroline_mytool, method: onDesktopOpen }

### Listener implementation

    public function onWorkspaceOpen(DisplayToolEvent $event)
    {
        $event->setContent($this->workspace($event->getWorkspace()->getId()));
    }

    public function onDesktopOpen(DisplayToolEvent $event)
    {
        $event->setContent($this->desktop());
    }

    private function workspace($workspaceId)
    {
        //if you want to keep the context, you must retrieve the workspace.
        $em = $this->container->get('doctrine.orm.entity_manager');
        $workspace = $em->getRepository('ClarolineCoreBundle:Workspace\AbstractWorkspace')->find($workspaceId);

        return $this->container->get('templating')->render(
            'ClarolineExampleBundle::workspace_tool.html.twig', array('workspace' => $workspace)
        );
    }

    private function desktop()
    {
        return $this->container->get('templating')->render(
            'ClarolineExampleBundle::desktop_tool.html.twig'
        );
    }

As you can see, if a tool is displayed in a workspace, you can know the current context
using $event->getWorkspace();
Then the workspace_tool.html.twig template must extends {% extends 'ClarolineCoreBundle:Workspace:layout.html.twig' %}

## Translations

* tools.xx.yml

We use lower case for every translation keys.

Create the *tools* file in your Resources/translations folder.
You can translate your widget names here.

    claroline_mytool: mytranslation

Where mywidgetname is the name you defined in your config file.
