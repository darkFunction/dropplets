# Initial architecture for new navigation scheme 
- Sam
- darkFunction
- 2014/05/16
- Navigation 
- draft 

After some planning and discussion with Dan and Miguel, we have a working first version of a new way of moving between viewControllers in our app(s). We should get together as a team to discuss, but I thought I would outline the recent work to clue everybody in first, in the spirit of sharing information as raised at the last retrospective. *This is not set in stone so please give your input*.

### Goal
To move away from URL-based navigation, but still have a simple way to navigate between screens in the application, with full animation, without coupling controllers together.

### Why not use standard segues on storyboards?
We would have to maintain each individual possible path, both to screens and unwinding from screens. To perform a complex transition from a screen deep in one stack, to another in a different stack, would involve creating an unwind segue with a unique id, and handling this id in the unwind destination in order to perform another segue. Too many bespoke pathways! It would be cool if we could automate as much as possible.

### New Idea
We represent all the screens in our application internally as a node tree of **Screens**, and are able to jump between them arbitrarily by calculating the path between the source and the destination and performing the correct actions. This node tree is stored in the database, and each Screen is built at launch time from static methods on the viewControllers.

![Navigation](http://notes.darkfunction.com/images/navtree.png)

For example, we can represent a transition from *Chat* to *Own Profile* as a path of *Screens*:

*Messages -> Menu -> Own Profile*

Each screen just needs to know which children it can present, that's it. 

### Example Usage
Let's look at the simple use case of clicking on a message in MessagesController and opening a ChatFlowViewController.

![Navigation](http://notes.darkfunction.com/images/navtree2.png)

#### The API 
	[[BPUIScreenSegue segueFromController:self toScreenIdentifier:@"Chat"] perform];

#### The Setup
##### BMAMessagesController.m
	// Our node tree is stored in the database, and is populated at launch-time 
	// by each controller in its load method.
	+ (void)load {
	    [self loadScreenIntoDatabase];
	}
	
	// Create the Screen. It is called "Messages" and has one child Screen, "Chat"
	// This is called from the load method and stored in the database.
	+ (BPUIScreen *)screen {
	    BPUIScreen *screen = [[BPUIScreen alloc] init];
	    screen.identifier = [self screenIdentifier];
	    screen.controllerClassName = NSStringFromClass([self class]);
	    screen.childScreenIdentifiers = @[ @"Chat" ];
	    return screen;
	}
	
	+ (NSString *)screenIdentifier {
	    return @"Messages";
	}

##### BMAChatFlowViewController.m
	+ (void)load {
	    [self loadScreenIntoDatabase];
	}
	
	+ (BPUIScreen *)screen {
	    BPUIScreen *screen = [[BPUIScreen alloc] init];
	    screen.identifier = [self screenIdentifier];
	    screen.controllerClassName = NSStringFromClass([self class]);
	    return screen;
	}	

	+ (NSString *)screenIdentifier {
	    return @"Chat";
	}

#### Dependency Injection
Dan's implementation of dependency containers. If you need to supply dependencies to the destination, it will be done from the originating viewController (the initiator of the navigation), by implementing *BPUIDependencyInjectionSource*. 

For example:
##### BMAMessagesController.m
	#pragma mark - BPUIDependencyInjectionSource 

	- (id<BPUIDependencyContainer>)dependencyContainerForProtocol:(Protocol *)protocol sender:(id)sender {
	    if (BPUIProtocolIsEqual(protocol, @protocol(BMAChatFlowViewControllerDescriptor))) {
	        
	        BMAChatFlowViewControllerDescriptor *dependencyContainer = [[BMAChatFlowViewControllerDescriptor alloc] init];
	        BMAMessagesUserData *user = [self.dataProvider userAtIndex:self.selectedIndexPath.row];
	        dependencyContainer.person = user;
	        
	        return dependencyContainer;
	    }
	    return nil;
	}
##### BMAChatFlowViewController.m
	#pragma mark - BPUIDependencyInjectionDestination
	
	+ (Protocol *)expectedDependencyContainerInterface {
	    return @protocol(BMAChatFlowViewControllerDescriptor);
	}
	
	- (void)injectDependenciesFromContainer:(id <BPUIDependencyContainer>)container {
	    id<BMAChatFlowViewControllerDescriptor> chatDescriptor = (id<BMAChatFlowViewControllerDescriptor>)container;
	
	    self.otherPersonId = chatDescriptor.person.uid;
	    self.name = chatDescriptor.person.name;
	    self.age = @(chatDescriptor.person.age);
	    self.isDeletedUser = chatDescriptor.person.isDeleted;
	    self.anonymous = chatDescriptor.person.isAnonymous;
	    
	    chatDescriptor = nil;
	}

### That's it...
For usage.

### How it works... BPUIScreenSegue
The logic is basic-- we find the path through our Screen nodes, which has a *backwards* component and a *forwards* component. This is just the shortest distance to the destination Screen, so we go back up the tree until we have a forwards path available. This might be as far as the menu and it might not. We loop through the steps backward, popping from the main navigationController, and then loop through the steps forward, creating each viewController (each Screen has a *controllerClassName* property), injecting dependencies and then pushing onto the stack. If we traverse through the menu, it is handled as a special case (the root node) and we perform menu animation. All animations are chained and it works pretty well!

#### Problems
- *We are assuming a simple push/pop relationship between controllers* (actually, we can handle modals like this as well). I made a start on calling storyboard segue transitions but this involves a fair bit of swizzling and means we duplicate navigation information in our Storyboard and in our Screen node tree. We might not feel that this is neccessary- it's up for debate. It *is* possible but it's something we need to discuss further as a team.

- *Multiple parents*-- sometimes we might not be able to easily identify the correct path to take, if a screen has more than one parent (eg, Other's Profile is a child of both Encounters and Chat, on Badoo). Still thinking about this one. Either we supply additional information to the navigation call, or we have the ability to define more than one screen per viewController... or something.

#### The Good
- Consise call to perform navigation. 

		[[BPUIScreenSegue segueFromController:self toScreenIdentifier:@"Encounters"] perform];

- The code for creating the screens is a one-time setup and is done in each controller. Dependency injection means no controller ever needs knowledge about another.

### Code
Latest code is sitting in [this ticket](https://jira.badoojira.com/browse/IOSBP-156).

It is very important that we do this right as our last two attempts at navigation (*Three20*, and *BMANavigator*) got us into a mess!
